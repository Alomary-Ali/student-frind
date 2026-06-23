@props([
    'items' => [],
])

@php
$defaultItems = [
    ['label' => 'الرئيسية', 'route' => 'home', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['label' => 'الأكاديمي', 'route' => 'academic.dashboard', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
    ['label' => 'الإنتاجية', 'route' => 'productivity.dashboard', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z'],
    ['label' => 'الملف', 'route' => 'academic.profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
];

$navItems = !empty($items) ? $items : $defaultItems;
@endphp

<nav
    class="rf-bottom-nav fixed bottom-0 inset-x-0 z-50 lg:hidden
           bg-surface border-t border-border shadow-lg
           flex items-center justify-around h-14 px-2"
    role="navigation"
    aria-label="القائمة السفلية"
>
    @foreach($navItems as $item)
        @php
            $isActive = request()->routeIs($item['route'] . '*');
        @endphp
        <a
            href="{{ route($item['route']) }}"
            class="rf-bottom-nav-link flex flex-col items-center justify-center gap-0.5
                   min-w-0 flex-1 h-full rounded-lg transition-colors duration-150
                   {{ $isActive ? 'text-primary' : 'text-text-muted hover:text-text-secondary' }}"
            @if($isActive) aria-current="page" @endif
            aria-label="{{ $item['label'] }}"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
            </svg>
            <span class="text-[10px] font-bold leading-tight">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
