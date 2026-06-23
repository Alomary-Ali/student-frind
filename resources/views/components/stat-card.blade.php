@props([
    'title',
    'value',
    'description' => null,
    'icon' => null,
    'trend' => null,
    'trendDirection' => 'up', // up, down
])

<div class="dashboard-card bg-surface border-border border rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-text-primary">{{ $title }}</h3>
        @if($icon)
            <span class="p-2 rounded-lg bg-primary/10 text-primary">
                {{ $icon }}
            </span>
        @endif
    </div>
    <div class="text-3xl font-bold text-text-primary mb-1">{{ $value }}</div>
    @if($description)
        <p class="text-xs text-text-secondary">{{ $description }}</p>
    @endif
    @if($trend)
        <div class="flex items-center gap-2 mt-3">
            @if($trendDirection === 'up')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                </svg>
            @endif
            <span class="text-xs {{ $trendDirection === 'up' ? 'text-accent' : 'text-error' }}">{{ $trend }}</span>
        </div>
    @endif
</div>
