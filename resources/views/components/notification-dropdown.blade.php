@props(['notifications' => []])

<div class="relative group">
    <button class="relative p-2 rounded-xl hover:bg-surface transition-colors">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(count($notifications) > 0)
        <x-notification-badge :count="count($notifications)" />
        @endif
    </button>

    <div class="absolute right-0 mt-2 w-80 bg-background rounded-xl shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50" style="border-color: hsl(var(--color-border));">
        <div class="p-4 border-b" style="border-color: hsl(var(--color-border));">
            <h3 class="font-bold">الإشعارات</h3>
            <p class="text-xs text-text-muted">{{ count($notifications) }} إشعار جديد</p>
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
            <a href="{{ route('notifications.show', $notification->id) }}" class="block p-4 border-b hover:bg-surface transition-colors" style="border-color: hsl(var(--color-border));">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $notification->type === 'success' ? 'bg-green-500' : ($notification->type === 'warning' ? 'bg-yellow-500' : ($notification->type === 'error' ? 'bg-red-500' : 'bg-blue-500')) }}">
                        @if($notification->type === 'success')
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        @elseif($notification->type === 'warning')
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                        </svg>
                        @elseif($notification->type === 'error')
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        @else
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $notification->title }}</p>
                        <p class="text-xs text-text-muted truncate">{{ $notification->message }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-4 text-center text-text-muted text-sm">
                لا توجد إشعارات جديدة
            </div>
            @endforelse
        </div>
        <div class="p-4 border-t" style="border-color: hsl(var(--color-border));">
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm w-full">عرض جميع الإشعارات</a>
        </div>
    </div>
</div>
