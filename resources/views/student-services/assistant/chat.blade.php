@extends('layouts.dashboard')

@section('title', 'المساعد الذكي')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">المساعد الذكي</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">المساعد الذكي</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">اسأل عن الخدمات الطلابية واحصل على إجابات فورية.</p>
            </div>
            <a href="{{ route('student-services.assistant.history') }}" class="btn btn-sm" style="background: hsl(var(--color-surface) / 0.15); color: hsl(var(--color-surface));">سجل المحادثات</a>
        </div>
    </div>

    <x-rf-card class="flex flex-col h-[600px]">
        {{-- Messages Area --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto space-y-4 p-4" style="min-height: 400px; max-height: 480px;">
            @if(empty($messages))
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: var(--gradient-navy);">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg">مرحباً بك في المساعد الذكي</h3>
                <p class="text-sm mt-2 max-w-md mx-auto">يمكنني مساعدتك في معرفة إجراءات الخدمات الطلابية، متطلبات تقديم الطلبات، والإجابة عن استفساراتك.</p>
                <p class="text-xs mt-4 text-text-muted">اختر أحد الاقتراحات أدناه للبدء</p>
            </div>
            @else
            @foreach($messages as $message)
            <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] {{ $message->role === 'user' ? 'bg-primary text-white rounded-2xl rounded-br-sm' : 'rounded-2xl rounded-bl-sm' }} px-4 py-3" style="{{ $message->role === 'user' ? '' : 'background: hsl(var(--color-surface) / 0.1);' }}">
                    <p class="text-sm">{{ $message->content }}</p>
                    <p class="text-xs mt-1 {{ $message->role === 'user' ? 'text-white/60' : 'text-text-muted' }}">{{ $message->createdAt }}</p>
                </div>
            </div>
            @endforeach
            @endif

            <div id="chat-loading" class="hidden justify-start">
                <div class="max-w-[80%] rounded-2xl rounded-bl-sm px-4 py-3" style="background: hsl(var(--color-surface) / 0.1);">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 rounded-full bg-primary animate-bounce"></div>
                        <div class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 0.1s;"></div>
                        <div class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 0.2s;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suggestions --}}
        @if(!empty($suggestions))
        <div class="px-4 py-2 border-t flex flex-wrap gap-2" style="border-color: hsl(var(--color-border-light));">
            @foreach($suggestions as $suggestion)
            <button onclick="sendSuggestion('{{ $suggestion->title }}')" class="text-xs px-3 py-1.5 rounded-full border hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" style="border-color: hsl(var(--color-border));">
                {{ $suggestion->title }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Input Area --}}
        <div class="border-t p-4" style="border-color: hsl(var(--color-border));">
            <form id="chat-form" method="POST" action="{{ route('student-services.assistant.send') }}" class="flex gap-3">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversationId ?? '' }}">
                <input type="text" name="message" id="chat-input" required placeholder="اكتب رسالتك هنا..." class="flex-1 rounded-xl border px-4 py-2.5 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary" style="border-color: hsl(var(--color-border));">
                <button type="submit" id="chat-send-btn" class="btn btn-primary btn-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </x-rf-card>
</div>
@endsection

@push('scripts')
<script>
    const messagesContainer = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatLoading = document.getElementById('chat-loading');
    const chatSendBtn = document.getElementById('chat-send-btn');

    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        const userBubble = document.createElement('div');
        userBubble.className = 'flex justify-end';
        userBubble.innerHTML = `<div class="max-w-[80%] bg-primary text-white rounded-2xl rounded-br-sm px-4 py-3"><p class="text-sm">${escapeHtml(message)}</p></div>`;
        messagesContainer.appendChild(userBubble);

        chatInput.value = '';
        chatLoading.classList.remove('hidden');
        chatLoading.className = 'flex justify-start';
        chatSendBtn.disabled = true;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        const formData = new FormData(chatForm);
        fetch('{{ route('student-services.assistant.send') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            chatLoading.classList.add('hidden');
            chatSendBtn.disabled = false;

            if (data.message) {
                const assistantBubble = document.createElement('div');
                assistantBubble.className = 'flex justify-start';
                assistantBubble.innerHTML = `<div class="max-w-[80%] rounded-2xl rounded-bl-sm px-4 py-3" style="background: hsl(var(--color-surface) / 0.1);"><p class="text-sm">${escapeHtml(data.message)}</p></div>`;
                messagesContainer.appendChild(assistantBubble);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        })
        .catch(() => {
            chatLoading.classList.add('hidden');
            chatSendBtn.disabled = false;
        });
    });

    function sendSuggestion(text) {
        chatInput.value = text;
        chatForm.dispatchEvent(new Event('submit'));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
