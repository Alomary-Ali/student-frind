@props([
    'variant' => 'info',
    'title' => null,
])

@php
$colors = [
    'info'    => '!bg-primary/10 !border-primary/30 text-primary',
    'success' => '!bg-accent/10 !border-accent/30 text-accent',
    'warning' => '!bg-warning/10 !border-warning/30 text-warning',
    'error'   => '!bg-error/10 !border-error/30 text-error',
];

$icons = [
    'info'    => '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'success' => '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'warning' => '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'error'   => '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
];
@endphp

<div
    x-data="{ visible: true }"
    x-show="visible"
    x-init="setTimeout(() => visible = false, 4000)"
    x-transition:leave="transition ease-out duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    role="alert"
    aria-live="polite"
    {{
        $attributes->merge([
            'class' => 'rf-toast flex items-start gap-3 p-4 rounded-xl border shadow-lg '
                . ($colors[$variant] ?? $colors['info']),
        ])
    }}
>
    <span class="rf-toast-icon shrink-0">{!! $icons[$variant] ?? $icons['info'] !!}</span>

    <div class="rf-toast-content flex-1 min-w-0">
        @if($title)
            <p class="rf-toast-title font-bold text-sm">{{ $title }}</p>
        @endif
        <p class="rf-toast-message text-sm opacity-80">{{ $slot }}</p>
    </div>

    <button
        type="button"
        @click="visible = false"
        class="rf-toast-close shrink-0 p-0.5 rounded hover:opacity-70 transition-opacity"
        aria-label="إغلاق"
    >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
