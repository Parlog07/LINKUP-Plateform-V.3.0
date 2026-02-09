<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;



class MessagesPage extends Component
{
    public User $receiver;
    public Conversation $conversation;
    public ?Collection $chatMessages = null;  // Collection of conversation messages
    public string $body = '';

    protected $rules = [
        'body' => 'required|string|max:1000',
    ];

    protected $listeners = ['messageReceived'];

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
    }




    public function sendMessage()
    {
        $this->validate();

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
        ]);

        broadcast(new PrivateMessageSent($message))->toOthers();

        $this->chatMessages->push($message->load('user'));

        $this->reset('body');
        
        // Trigger scroll event
        $this->dispatch('scrollToBottom');
    }

    public function messageReceived($payload)
    {
        // Ensure we have a message object
        if (isset($payload['message'])) {
            $message = is_array($payload['message'])
                ? (object) $payload['message']
                : $payload['message'];

            $this->chatMessages->push($message);
            $this->dispatch('scrollToBottom');
        }
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

    $conversation = Conversation::firstOrCreate(
        ['user_one_id' => $first, 'user_two_id' => $second],
        [] // extra defaults if needed
    );

    // Either redirect or emit event / set state
    return redirect()->route('messages.show', $conversation);
}
}