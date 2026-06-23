@props([
    'label' => 'خيارات',
    'align' => 'start',
    'items' => [],
])

@php
$alignClass = $align === 'end' ? 'left-0 right-auto' : 'right-0 left-auto';
@endphp

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="rf-dropdown relative inline-block"
>
    {{-- Trigger --}}
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open"
        class="rf-dropdown-trigger inline-flex items-center gap-2 px-4 py-2 rounded-xl
               bg-surface border border-border text-text-secondary hover:text-text-primary
               hover:bg-background transition-colors text-sm font-bold"
        aria-haspopup="true"
    >
        {{ $label }}
        <svg
            class="w-4 h-4 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Menu --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="rf-dropdown-menu absolute z-50 mt-1 min-w-[180px] {{ $alignClass }}
               bg-surface rounded-xl shadow-lg border border-border py-1"
        role="menu"
    >
        @foreach($items as $item)
            @if(isset($item['divider']))
                <div class="rf-dropdown-divider my-1 border-t border-border"></div>
            @elseif(isset($item['action']))
                <button
                    type="button"
                    @click="open = false; {{ $item['action'] }}"
                    class="rf-dropdown-item w-full text-right px-4 py-2 text-sm font-medium
                           text-text-secondary hover:text-text-primary hover:bg-background
                           transition-colors {{ $item['danger'] ?? false ? '!text-error hover:!bg-error/10' : '' }}"
                    role="menuitem"
                >
                    @if(isset($item['icon']))
                        <span class="inline-flex items-center gap-2">
                            <span class="w-4 h-4 shrink-0" aria-hidden="true">{!! $item['icon'] !!}</span>
                            {{ $item['label'] }}
                        </span>
                    @else
                        {{ $item['label'] }}
                    @endif
                </button>
            @else
                <a
                    href="{{ $item['url'] ?? '#' }}"
                    class="rf-dropdown-item block px-4 py-2 text-sm font-medium
                           text-text-secondary hover:text-text-primary hover:bg-background
                           transition-colors {{ $item['danger'] ?? false ? '!text-error hover:!bg-error/10' : '' }}"
                    role="menuitem"
                >
                    @if(isset($item['icon']))
                        <span class="inline-flex items-center gap-2">
                            <span class="w-4 h-4 shrink-0" aria-hidden="true">{!! $item['icon'] !!}</span>
                            {{ $item['label'] }}
                        </span>
                    @else
                        {{ $item['label'] }}
                    @endif
                </a>
            @endif
        @endforeach

        {{ $slot }}
    </div>
</div>
