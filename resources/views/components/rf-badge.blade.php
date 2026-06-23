@props([
    'variant' => 'primary',
    'size' => 'md',
    'dot' => false,
    'pill' => true,
])

@php
$variantClasses = [
    'primary'   => 'badge-primary',
    'accent'    => 'badge-accent',
    'warning'   => 'badge-warning',
    'error'     => 'badge-error',
    'navy'      => 'badge-navy',
    'muted'     => 'badge-muted',
];

$sizeClasses = [
    'sm' => 'text-[10px] px-1.5 py-0.5',
    'md' => '',
];
@endphp

<span {{
    $attributes->merge([
        'class' => 'rf-badge badge '
            . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' '
            . ($sizeClasses[$size] ?? $sizeClasses['md']),
    ])
}}>
    @if($dot)
        <span class="rf-badge-dot status-dot {{ match($variant) {
            'primary' => 'bg-primary',
            'accent' => 'bg-accent',
            'warning' => 'bg-warning',
            'error' => 'bg-error',
            'navy' => 'bg-navy',
            default => 'bg-primary',
        } }}" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</span>
