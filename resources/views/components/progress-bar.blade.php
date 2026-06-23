@props([
    'value' => 0,
    'max' => 100,
    'color' => 'primary', // primary, accent, error
    'height' => 'h-2',
])

@php
$colors = [
    'primary' => 'bg-primary',
    'accent' => 'bg-accent',
    'error' => 'bg-error',
];

$percentage = min(max(($value / $max) * 100, 0), 100);
@endphp

<div class="w-full {{ $height }} bg-background rounded-full overflow-hidden">
    <div class="{{ $height }} {{ $colors[$color] ?? $colors['primary'] }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
</div>
