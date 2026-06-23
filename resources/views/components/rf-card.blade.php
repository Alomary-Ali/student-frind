@props([
    'title' => null,
    'description' => null,
    'variant' => 'default',
    'padding' => true,
    'header' => null,
    'footer' => null,
])

@php
$variantClasses = [
    'default'  => 'card',
    'elevated' => 'card-elevated',
    'navy'     => 'card-navy',
    'primary'  => 'card-primary',
    'info'     => 'card-info',
    'success'  => 'card-success',
    'warning'  => 'card-warning',
    'error'    => 'card-error',
];

$paddingClass = $padding ? 'p-4 md:p-5' : '';
@endphp

<div {{ $attributes->merge(['class' => 'rf-card dashboard-card ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . $paddingClass]) }}>
    @if($header)
        <div class="rf-card-header {{ $padding ? '-mx-4 md:-mx-5 px-4 md:px-5 pb-4 md:pb-5 border-b border-border' : 'mb-4' }}">
            {{ $header }}
        </div>
    @elseif($title || $description)
        <div class="rf-card-header mb-4">
            @if($title)
                <h3 class="rf-card-title heading-premium">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="rf-card-description text-sm text-text-secondary mt-1">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="rf-card-body">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="rf-card-footer mt-4 {{ $padding ? '-mx-4 md:-mx-5 px-4 md:px-5 pt-4 md:pt-5 border-t border-border' : '' }}">
            {{ $footer }}
        </div>
    @endif
</div>
