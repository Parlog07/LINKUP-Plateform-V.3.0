<?php

namespace App\Livewire;

use App\Events\MessageSent;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;



class MessagesPage extends Component
{
    use WithFileUploads;

    public User $receiver;
    public Conversation $conversation;
    public ?Collection $chatMessages = null;  // Collection of conversation messages
    public string $body = '';
    public $attachment = null;
    public bool $disappearingEnabled = false;

    protected $rules = [
        'body' => 'nullable|string|max:1000|required_without:attachment',
        'attachment' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm,m4v,avi,mkv,pdf,doc,docx,txt,zip,rar',
    ];

    public function mount(User $user)
    {
        // Initialize chatMessages first to avoid uninitialized property error
        $this->chatMessages = collect([]);

        $authId = Auth::id();

        if (! $authId) {
            redirect()->route('login')->send();
            return;
        }

        if ($user->id === $authId) {
            session()->flash('error', 'You cannot message yourself.');
            redirect()->route('dashboard')->send();
            return;
        }

        $this->receiver = $user;

        // Ensure both IDs are valid integers
        $userOneId = min($authId, $user->id);
        $userTwoId = max($authId, $user->id);

        if (! $userOneId || ! $userTwoId) {
            abort(500, 'Invalid user IDs for conversation');
        }

        $this->conversation = Conversation::firstOrCreate([
            'user_one' => $userOneId,
            'user_two' => $userTwoId,
        ]);

        $this->chatMessages = $this->conversation
            ->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get();

        $this->disappearingEnabled = $this->conversation->disappearing_enabled;
    }




    public function sendMessage()
    {
        $this->validate();

        $body = trim($this->body);
        if ($body === '' && ! $this->attachment) {
            return;
        }

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;
        $attachmentSize = null;

        if ($this->attachment) {
            $attachmentName = $this->attachment->getClientOriginalName();
            $attachmentMime = $this->attachment->getClientMimeType();
            $attachmentSize = $this->attachment->getSize();
            $attachmentPath = $this->attachment->store('chat-attachments', 'public');
        }

        // Create the message
        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => Auth::id(),
            'body' => $body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_mime' => $attachmentMime,
            'attachment_size' => $attachmentSize,
            'expires_at' => $this->conversation->disappearing_enabled
                ? now()->addHours(24)
                : null,
        ]);

        $message->load('user');

        $this->chatMessages->push($message);

        $this->body = '';
        $this->attachment = null;

        broadcast(new PrivateMessageSent($message))->toOthers();
        $conversation = $message->conversation;

        $receiverId = $conversation->user_one == $message->user_id
            ? $conversation->user_two
            : $conversation->user_one;

        broadcast(new MessageSent($message, $receiverId))->toOthers();



        $this->dispatch('scroll-to-bottom');
    }

    #[On('message-received')]
    public function messageReceived($message = null)
    {
        $messageId = is_array($message) ? ($message['id'] ?? null) : null;
        $message = Message::with('user')->find($messageId);

        if (! $message) {
            return;
        }

        $this->chatMessages->push($message);
        $this->dispatch('scroll-to-bottom');
    }


    public function render()
    {
        return view('livewire.messages-page');
    }


    public function startChat(int $userTwoId)
    {
        $userOneId = Auth::id();            // current logged-in user
        abort_if($userOneId === null, 403);
        abort_if($userOneId === $userTwoId, 403);

        // Normalize order so each pair has only one conversation row
        [$first, $second] = $userOneId < $userTwoId
            ? [$userOneId, $userTwoId]
            : [$userTwoId, $userOneId];

        $conversation = Conversation::firstOrCreate([
            'user_one' => $first,
            'user_two' => $second,
        ]);

        // Either redirect or emit event / set state
        return redirect()->route('messages.show', $userTwoId);
    }
    public function toggleDisappearing()
    {
        $this->disappearingEnabled = ! $this->disappearingEnabled;

        $this->conversation->update([
            'disappearing_enabled' => $this->disappearingEnabled,
        ]);

        broadcast(new \App\Events\DisappearingToggled(
            $this->conversation->id,
            $this->disappearingEnabled
        ))->toOthers();
    }

    protected function getListeners()
    {
        return [
            "echo-private:conversation.{$this->conversation->id},DisappearingToggled" => 'handleToggle',
        ];
    }

    public function handleToggle($event)
    {
        $this->disappearingEnabled = $event['enabled'];
    }
}
