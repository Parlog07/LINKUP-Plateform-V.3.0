<div class="max-w-4xl mx-auto h-[85vh] flex flex-col bg-white shadow-xl rounded-2xl overflow-hidden">

    <!-- Header -->
    <div class="flex items-center gap-3 border-b px-6 py-4 bg-gray-50">
        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
            {{ strtoupper(substr($receiver->first_name ?? 'U', 0, 1)) }}
        </div>
        <div>
            <h2 class="font-semibold text-lg leading-tight">
                {{ $receiver->first_name }} {{ $receiver->last_name }}
            </h2>
            <p class="text-xs text-gray-500" id="user-status">
    Offline
</p>

<button 
    wire:click="toggleDisappearing"
    class="px-3 py-1 rounded text-sm font-medium
        {{ $disappearingEnabled 
            ? 'bg-green-500 text-white' 
            : 'bg-gray-300 text-gray-800' }}">
    
    {{ $disappearingEnabled 
        ? 'Disappearing: ON (24h)' 
        : 'Disappearing: OFF' }}
</button>



@if($conversation->disappearingEnabled)
    <span class="text-sm text-gray-500">
        Messages disappear after 24 hours
    </span>
@endif

        </div>
    </div>

    <!-- Messages -->
    <div
        id="messages-container"
        data-conversation-id="{{ $conversation->id }}"
        class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gradient-to-b from-gray-50 to-white"
    >
        @forelse($chatMessages as $msg)
            @php $isMe = $msg->user_id === auth()->id(); @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%]">
                    @if(!$isMe)
                        <span class="text-xs text-gray-500 ml-1 mb-1 block">
                            {{ $msg->user->first_name }} {{ $msg->user->last_name }}
                        </span>
                    @endif
                    <div class="relative px-4 py-2 rounded-2xl text-sm leading-relaxed
                        {{ $isMe ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-gray-200 text-gray-900 rounded-bl-sm' }}">
                        {{ $msg->body }}
                    </div>
                    <div class="text-[11px] text-gray-400 mt-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full text-gray-400">
                No messages yet. Say hi 👋
            </div>
        @endforelse
    </div>

    <!-- Input -->
    <div class="border-t px-6 py-4 bg-white sticky bottom-0">
        <form wire:submit.prevent="sendMessage">
            <div class="flex items-center gap-3">
                <input
                    wire:model="body"
                    type="text"
                    placeholder="Type a message…"
                    autocomplete="off"
                    class="flex-1 border border-gray-300 rounded-full px-5 py-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    wire:keydown.enter.prevent="sendMessage"
                >
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="sendMessage"
                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-6 py-3
                           rounded-full font-semibold transition shadow"
                >
                    <span wire:loading.remove wire:target="sendMessage">Send</span>
                    <span wire:loading wire:target="sendMessage">Sending...</span>
                </button>
            </div>
            @error('body')
                <p class="text-red-500 text-xs mt-2 ml-2">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    const container = document.getElementById('messages-container');
    if (!container || !window.Echo || !window.Livewire) return;

    const conversationId = @json($conversation->id);
    const receiverId = @json($receiver->id);
    const statusElement = document.getElementById('user-status');
    const channelName = `conversation.${conversationId}`;

    window.Echo.leave(channelName);

    window.Echo.join(channelName)
        .here((users) => {
            const receiverIsOnline = users.some((user) => Number(user.id) === Number(receiverId));
            if (statusElement) {
                statusElement.textContent = receiverIsOnline ? 'Active now' : 'Online';
            }
        })
        .joining((user) => {
            if (Number(user.id) === Number(receiverId) && statusElement) {
                statusElement.textContent = 'Active now';
            }
        })
        .leaving((user) => {
            if (Number(user.id) === Number(receiverId) && statusElement) {
                statusElement.textContent = 'Offline';
            }
        })
        .listen('.message.sent', (e) => {
            if (e?.message) {
                window.Livewire.dispatch('message-received', { message: e.message });
            }
        });

    window.Livewire.on('scroll-to-bottom', () => {
        container.scrollTop = container.scrollHeight;
    });
});

</script>
@endpush
