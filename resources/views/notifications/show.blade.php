@extends('layouts.dashboard')

@section('title', 'تفاصيل الإشعار')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">الإشعارات</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">تفاصيل الإشعار</h1>
        </div>
    </div>

    <x-rf-card>
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $notification->type === 'success' ? 'bg-green-500' : ($notification->type === 'warning' ? 'bg-yellow-500' : ($notification->type === 'error' ? 'bg-red-500' : 'bg-blue-500')) }}">
                    @if($notification->type === 'success')
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    @elseif($notification->type === 'warning')
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @elseif($notification->type === 'error')
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    @else
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold">{{ $notification->title }}</h2>
                    <p class="text-text-muted mt-1">{{ \Modules\Notifications\Domain\Enums\NotificationType::tryFrom($notification->type)?->label() ?? $notification->type }}</p>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <p class="text-sm text-text-muted mb-2">الرسالة</p>
                <p class="text-base">{{ $notification->message }}</p>
            </div>

            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-text-muted">القناة</p>
                        <p class="font-medium">{{ \Modules\Notifications\Domain\Enums\NotificationChannel::tryFrom($notification->channel)?->label() ?? $notification->channel }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted">تاريخ الإنشاء</p>
                        <p class="font-medium">{{ $notification->createdAt }}</p>
                    </div>
                </div>
            </div>

            @if($notification->link)
            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <a href="{{ $notification->link }}" class="btn btn-primary">الانتقال للرابط</a>
            </div>
            @endif

            @if(!$notification->isRead)
            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary">تحديد كمقروء</button>
                </form>
            </div>
            @endif
        </div>
    </x-rf-card>

    <a href="{{ route('notifications.index') }}" class="btn btn-secondary">عودة للإشعارات</a>
</div>
@endsection
