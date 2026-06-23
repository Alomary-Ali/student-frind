@props([
    'title' => '',
    'description' => '',
    'icon' => null,
    'action' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'rf-empty-state flex flex-col items-center justify-center text-center py-12 px-4']) }}>
    @if($icon)
        <div class="rf-empty-icon mb-4 text-text-muted/50" aria-hidden="true">
            {!! $icon !!}
        </div>
    @else
        <div class="rf-empty-icon mb-4 text-text-muted/30" aria-hidden="true">
            <svg class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
    @endif

    @if($title)
        <h3 class="rf-empty-title text-lg font-bold text-text-primary mb-1">{{ $title }}</h3>
    @endif

    @if($description)
        <p class="rf-empty-description text-sm text-text-secondary max-w-xs">{{ $description }}</p>
    @endif

    @if($action && $actionLabel)
        <div class="rf-empty-action mt-5">
            {{ $action }}
        </div>
    @endif

    {{ $slot }}
</div>
