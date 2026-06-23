@props([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'label' => null,
    'showPercentage' => true,
    'size' => 'md',
])

@php
$percentage = min(100, max(0, ($value / max(1, $max)) * 100));
$fillClass = match($variant) {
    'primary' => 'progress-fill-primary',
    'accent' => 'progress-fill-accent',
    'warning' => 'progress-fill-warning',
    'gradient' => 'progress-fill-gradient',
    default => 'progress-fill-primary',
};
$heightClass = match($size) {
    'sm' => 'h-1',
    'md' => 'h-1.5',
    'lg' => 'h-2.5',
    default => 'h-1.5',
};
@endphp

<div {{ $attributes->merge(['class' => 'rf-progress w-full']) }}>
    @if($label || $showPercentage)
        <div class="rf-progress-header flex items-center justify-between mb-1.5">
            @if($label)
                <span class="rf-progress-label text-xs font-bold text-text-secondary">{{ $label }}</span>
            @endif
            @if($showPercentage)
                <span class="rf-progress-value text-xs font-bold text-text-muted" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                    {{ round($percentage) }}%
                </span>
            @endif
        </div>
    @endif
    <div class="rf-progress-track progress-track {{ $heightClass }}" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
        <div class="rf-progress-fill progress-fill {{ $fillClass }}" style="width: {{ $percentage }}%"></div>
    </div>
</div>
