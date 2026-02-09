<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Collection;

class MessagesPage extends Component
{
    public User $receiver;
    public Conversation $conversation;
    public Collection $messages;  // Changed to Collection type
    public string $body = '';

    protected $rules = [
        'body' => 'required|string|max:1000',
    ];

    protected $listeners = ['messageReceived'];

public function mount(User $user)
{
    if ($user->id === auth()->id()) {
        session()->flash('error', 'You cannot message yourself.');
        return redirect()->route('dashboard');
    }

    $this->receiver = $user;

    $this->conversation = Conversation::firstOrCreate([
        'user_one' => min(auth()->id(), $user->id),
        'user_two' => max(auth()->id(), $user->id),
    ]);

    $this->messages = $this->conversation
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
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        broadcast(new PrivateMessageSent($message))->toOthers();

        $this->messages->push($message->load('user'));

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
            
            $this->messages->push($message);
            $this->dispatch('scrollToBottom');
        }
    }

    public function render()
    {
        return view('livewire.messages-page');
    }
}