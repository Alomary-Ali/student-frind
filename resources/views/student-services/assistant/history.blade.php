@extends('layouts.dashboard')

@section('title', 'سجل المحادثات')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">المساعد الذكي</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">سجل المحادثات</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">جميع محادثاتك السابقة مع المساعد الذكي.</p>
            </div>
            <a href="{{ route('student-services.assistant.chat') }}" class="btn btn-primary btn-sm">محادثة جديدة</a>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($conversations as $conversation)
        <a href="{{ route('student-services.assistant.conversation', $conversation->id) }}" class="block no-underline">
            <x-rf-card>
                <div class="flex justify-between items-center">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ $conversation->title ?? 'محادثة بدون عنوان' }}</p>
                        <p class="text-xs mt-1">{{ $conversation->lastActivityAt }}</p>
                    </div>
                    <x-rf-badge variant="{{ $conversation->status === 'active' ? 'primary' : ($conversation->status === 'closed' ? 'secondary' : 'ghost') }}">
                        {{ \Modules\StudentServices\Domain\Enums\ConversationStatus::tryFrom($conversation->status)?->label() ?? $conversation->status }}
                    </x-rf-badge>
                </div>
            </x-rf-card>
        </a>
        @empty
        <x-rf-empty-state title="لا توجد محادثات سابقة" description="لم تقم بإجراء أي محادثة مع المساعد الذكي بعد">
            <a href="{{ route('student-services.assistant.chat') }}" class="btn btn-primary btn-sm mt-4">ابدأ محادثة جديدة</a>
        </x-rf-empty-state>
        @endforelse
    </div>
</div>
@endsection
