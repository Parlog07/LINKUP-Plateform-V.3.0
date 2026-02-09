<div class="max-w-4xl mx-auto h-[85vh] flex flex-col bg-white shadow-xl rounded-2xl overflow-hidden">

    <!-- Header -->
    <div class="flex items-center gap-3 border-b px-6 py-4 bg-gray-50">
        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
            {{ strtoupper(substr($receiver->name, 0, 1)) }}
        </div>

        <div>
            <h2 class="font-semibold text-lg leading-tight">
                {{ $receiver->name }}
            </h2>
            <p class="text-xs text-gray-500">
                Active now
            </p>
        </div>
    </div>

     <div
        id="messages-container"
        class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gradient-to-b from-gray-50 to-white">
        @forelse($messages as $msg)
            @php $isMe = $msg->user_id === auth()->id(); @endphp

            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%]">

                    @if(!$isMe)
                        <span class="text-xs text-gray-500 ml-1 mb-1 block">
                            {{ $msg->user->name }}
                        </span>
                    @endif

                    <div
                        class="relative px-4 py-2 rounded-2xl text-sm leading-relaxed
                        {{ $isMe
                            ? 'bg-blue-600 text-white rounded-br-sm'
                            : 'bg-gray-200 text-gray-900 rounded-bl-sm'
                        }}"
                    >
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
    <form
        wire:submit.prevent="sendMessage"
        class="border-t px-6 py-4 bg-white sticky bottom-0"
    >
        <div class="flex items-center gap-3">
            <input
                wire:model.defer="body"
                type="text"
                placeholder="Type a message…"
                autocomplete="off"
                class="flex-1 border border-gray-300 rounded-full px-5 py-3 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3
                       rounded-full font-semibold transition shadow"
            >
                Send
            </button>
        </div>

        @error('body')
            <p class="text-red-500 text-xs mt-2 ml-2">{{ $message }}</p>
        @enderror
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Echo.private('conversation.{{ $conversation->id }}')
            .listen('.message.sent', (e) => {
                @this.call('messageReceived', { message: e.message });
            });
    });

    Livewire.on('scrollToBottom', () => {
        const container = document.getElementById('messages-container');
        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    });
</script>
@endpush
