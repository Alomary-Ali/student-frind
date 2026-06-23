@props([
    'items' => [],
    'home' => true,
])

<nav class="rf-breadcrumb flex items-center gap-1.5 text-sm mb-4" aria-label="مسار الصفحة">
    @if($home)
        <a href="{{ route('home') }}" class="rf-breadcrumb-link text-text-muted hover:text-primary transition-colors font-medium">
            <svg class="w-4 h-4 inline-block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-3.5 h-3.5 text-text-muted/50 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
        </svg>
    @endif

    @foreach($items as $index => $item)
        @if(isset($item['route']))
            <a href="{{ route($item['route']) }}" class="rf-breadcrumb-link text-text-muted hover:text-primary transition-colors font-medium">
                {{ $item['label'] }}
            </a>
        @else
            <span class="rf-breadcrumb-current text-text-primary font-bold" aria-current="page">
                {{ $item['label'] }}
            </span>
        @endif

        @if($index < count($items) - 1)
            <svg class="w-3.5 h-3.5 text-text-muted/50 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
            </svg>
        @endif
    @endforeach
</nav>
