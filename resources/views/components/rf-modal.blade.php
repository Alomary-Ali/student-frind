@props([
    'title' => null,
    'size' => 'md',
    'show' => false,
    'closeable' => true,
])

@php
$sizeClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-full mx-4',
];
@endphp

<div
    x-data="{ open: {{ $show ? 'true' : 'false' }} }"
    x-show="open"
    x-trap.noscroll="open"
    @keydown.escape.window="if({{ $closeable ? 'true' : 'false' }}) open = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="rf-modal fixed inset-0 z-[70] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    :aria-label="'{{ $title ?? 'نافذة منبثقة' }}'"
>
    {{-- Backdrop --}}
    <div
        class="rf-modal-backdrop absolute inset-0 bg-navy/40 backdrop-blur-sm"
        @click="if({{ $closeable ? 'true' : 'false' }}) open = false"
        aria-hidden="true"
    ></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="rf-modal-panel relative bg-surface rounded-2xl shadow-xl border border-border w-full {{ $sizeClasses[$size] ?? $sizeClasses['md'] }} max-h-[90vh] flex flex-col"
    >
        {{-- Header --}}
        @if($title || $closeable)
        <div class="rf-modal-header flex items-center justify-between px-6 pt-5 pb-3 border-b border-border">
            @if($title)
                <h2 class="rf-modal-title text-lg font-bold text-text-primary">{{ $title }}</h2>
            @endif
            @if($closeable)
                <button
                    type="button"
                    @click="open = false"
                    class="rf-modal-close p-1.5 rounded-lg text-text-muted hover:text-text-primary hover:bg-background transition-colors"
                    aria-label="إغلاق"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>
        @endif

        {{-- Body --}}
        <div class="rf-modal-body p-6 overflow-y-auto">
            {{ $slot }}
        </div>

        {{-- Footer (optional) --}}
        @isset($footer)
        <div class="rf-modal-footer px-6 pb-5 pt-3 border-t border-border">
            {{ $footer }}
        </div>
        @endisset
    </div>
</div>
