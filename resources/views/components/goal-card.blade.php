@props([
    'goal' => null,
])

<div class="unit-card p-4 hover:shadow-md transition-shadow">
    @if($goal)
        <div class="flex items-start justify-between mb-3">
            <h3 class="font-semibold text-base text-text-primary">{{ $goal->title }}</h3>
            @if($goal->target_date)
                <span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($goal->target_date)->format('Y-m-d') }}</span>
            @endif
        </div>
        <p class="text-sm mb-3 text-text-muted">{{ $goal->description ?? '' }}</p>
        <div class="w-full progress-track h-2">
            <div class="progress-fill progress-fill-accent h-2 rounded-full transition-all" style="width: {{ $goal->progress_percentage ?? 0 }}%"></div>
        </div>
        <div class="flex justify-between items-center mt-2">
            <span class="text-xs text-text-muted">{{ $goal->progress_percentage ?? 0 }}%</span>
            <span class="text-xs px-2 py-1 rounded-full {{ $goal->status === 'completed' ? 'badge-accent' : 'badge-navy' }}">
                {{ $goal->status }}
            </span>
        </div>
    @endif
</div>
