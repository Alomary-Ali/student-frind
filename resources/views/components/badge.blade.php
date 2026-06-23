@props([
    'variant' => 'default', // default, accent, error, warning
    'size' => 'md', // sm, md
])

@php
$variants = [
    'default' => 'bg-primary/10 text-primary',
    'accent' => 'bg-accent/10 text-accent',
    'error' => 'bg-error/10 text-error',
    'warning' => 'bg-warning/10 text-warning',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-[10px]',
    'md' => 'px-3 py-1 text-xs',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full font-medium ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    {{ $slot }}
</span>
