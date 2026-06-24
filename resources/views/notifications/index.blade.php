@extends('layouts.dashboard')

@section('title', 'الإشعارات')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">الإشعارات</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">الإشعارات</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">جميع الإشعارات الخاصة بك.</p>
            </div>
            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">تحديد الكل كمقروء</button>
            </form>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
        <x-rf-card class="{{ $notification->isRead ? 'opacity-75' : '' }}">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ $notification->type === 'success' ? 'bg-green-500' : ($notification->type === 'warning' ? 'bg-yellow-500' : ($notification->type === 'error' ? 'bg-red-500' : 'bg-blue-500')) }}">
                    @if($notification->type === 'success')
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    @elseif($notification->type === 'warning')
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @elseif($notification->type === 'error')
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    @else
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium {{ $notification->isRead ? 'text-text-muted' : '' }}">{{ $notification->title }}</p>
                            <p class="text-sm mt-1 {{ $notification->isRead ? 'text-text-muted' : '' }}">{{ $notification->message }}</p>
                            <p class="text-xs text-text-muted mt-2">{{ $notification->createdAt }}</p>
                        </div>
                        @if(!$notification->isRead)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-text-muted hover:text-primary">تحديد كمقروء</button>
                        </form>
                        @endif
                    </div>
                    @if($notification->link)
                    <a href="{{ $notification->link }}" class="btn btn-primary btn-sm mt-3">عرض التفاصيل</a>
                    @endif
                </div>
            </div>
        </x-rf-card>
        @empty
        <x-rf-empty-state title="لا توجد إشعارات" description="ستظهر الإشعارات هنا عند توفرها">
        </x-rf-empty-state>
        @endforelse
    </div>
</div>
@endsection
