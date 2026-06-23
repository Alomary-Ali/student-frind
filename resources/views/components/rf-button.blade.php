@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'loading' => false,
    'icon' => null,
    'fullWidth' => false,
])

@php
$variantClasses = [
    'primary'    => 'btn-primary',
    'secondary'  => 'btn-secondary',
    'accent'     => 'btn-accent',
    'ghost'      => 'btn-ghost',
    'destructive' => 'btn btn-sm !bg-error !text-surface hover:!bg-error-hover',
];

$sizeClasses = [
    'sm' => 'btn-sm',
    'md' => '',
    'lg' => 'h-12 px-8 text-base',
];
@endphp

<button
    type="{{ $type }}"
    {{
        $attributes->merge([
            'class' => 'btn rf-btn '
                . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' '
                . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' '
                . ($fullWidth ? 'btn-full ' : '')
                . ($loading ? 'opacity-70 pointer-events-none ' : ''),
        ])
    }}
    @if($loading) disabled aria-busy="true" @endif
>
    @if($icon && !$loading)
        <span class="rf-btn-icon" aria-hidden="true">{!! $icon !!}</span>
    @endif
    @if($loading)
        <svg class="animate-spin -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</button>
