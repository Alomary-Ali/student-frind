@props([
    'variant' => 'primary', // primary, accent, warning, error
    'size' => 'md', // sm, md, lg
])

@php
$sizeClasses = match($size) {
    'sm' => 'w-8 h-8',
    'md' => 'w-10 h-10',
    'lg' => 'w-12 h-12',
    default => 'w-10 h-10',
};

$variantClasses = match($variant) {
    'primary' => 'bg-primary/10 text-primary',
    'accent' => 'bg-accent/10 text-accent',
    'warning' => 'bg-warning/10 text-warning',
    'error' => 'bg-error/10 text-error',
    default => 'bg-primary/10 text-primary',
};
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-xl flex-shrink-0 ' . $sizeClasses . ' ' . $variantClasses]) }}>
    {{ $slot }}
</div>
