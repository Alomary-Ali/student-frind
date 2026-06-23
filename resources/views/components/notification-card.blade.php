@props([
    'notification' => null,
])

<div class="unit-card p-4 hover:shadow-md transition-shadow">
    @if($notification)
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                @if($notification->type === 'task_due' || $notification->type === 'deadline')
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-warning"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                @elseif($notification->type === 'goal_progress')
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-accent"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-navy"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-sm mb-1 text-text-primary">{{ $notification->message }}</h3>
                <span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    @endif
</div>
