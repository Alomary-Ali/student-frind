@props([
    'task' => null,
])

<div class="unit-card p-4 hover:shadow-md transition-shadow">
    @if($task)
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="font-semibold text-base mb-1 text-text-primary">{{ $task->title }}</h3>
                <p class="text-sm mb-2 text-text-muted">{{ $task->description ?? '' }}</p>
                <div class="flex items-center gap-2 text-xs text-text-muted">
                    @if($task->due_date)
                        <span>الموعد: {{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</span>
                    @endif
                    @if($task->priority)
                        <span class="px-2 py-1 rounded-full {{ $task->priority === 'urgent' ? 'badge-error' : ($task->priority === 'high' ? 'badge-warning' : 'badge-navy') }}">
                            {{ $task->priority }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($task->status === 'completed')
                    <span class="text-accent">✓</span>
                @endif
            </div>
        </div>
    @endif
</div>
