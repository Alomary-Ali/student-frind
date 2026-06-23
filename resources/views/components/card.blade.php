@props([
    'title' => null,
    'description' => null,
    'padding' => 'p-5',
    'border' => true,
    'shadow' => false,
    'variant' => 'default', // default, elevated, navy, primary, info, success, warning, error
])

@php
$variantClasses = match($variant) {
    'default' => 'card',
    'elevated' => 'card-elevated',
    'navy' => 'card-navy',
    'primary' => 'card-primary',
    'info' => 'card-info',
    'success' => 'card-success',
    'warning' => 'card-warning',
    'error' => 'card-error',
    default => 'card',
};
@endphp

<div {{ $attributes->merge(['class' => 'dashboard-card ' . $variantClasses . ' ' . $padding]) }}>
    @if($title || $description)
        <div class="mb-4">
            @if($title)
                <h3 class="heading-premium text-lg">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="text-sm text-text-secondary mt-1">{{ $description }}</p>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
