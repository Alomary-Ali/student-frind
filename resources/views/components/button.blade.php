@props([
    'variant' => 'primary', // primary, secondary, ghost, destructive
    'size' => 'md', // sm, md, lg
    'type' => 'button',
])

@php
$variants = [
    'primary' => 'bg-primary hover:bg-primary-hover text-surface',
    'secondary' => 'bg-surface border-border border text-text-primary hover:bg-background',
    'ghost' => 'bg-transparent text-primary hover:bg-primary/5',
    'destructive' => 'bg-error hover:bg-error-hover text-surface',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center justify-center rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    {{ $slot }}
</button>
