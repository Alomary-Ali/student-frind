@props([
    'variant' => 'student',
    'topOffset' => '84px',
    'topOffsetDesktop' => '96px',
    'showUser' => true,
    'showThemeToggle' => true,
    'navigation' => [],
    'userName' => null,
    'userAcademicId' => null,
    'userInitial' => null,
])

@php
$user = auth()->user();
$navItems = !empty($navigation) ? $navigation : [
    'main' => [
        ['label' => 'الرئيسية', 'route' => 'home', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ],
    'الأكاديمي' => [
        ['label' => 'لوحة القيادة', 'route' => 'academic.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['label' => 'المواد الدراسية', 'route' => 'academic.courses', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['label' => 'الخطة الدراسية', 'route' => 'academic.plan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label' => 'الملف الأكاديمي', 'route' => 'academic.profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['label' => 'مؤشرات الأداء', 'route' => 'academic.progress', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z'],
        ['label' => 'خريطة التخرج', 'route' => 'academic.graduation-map', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l5.447-2.724A1 1 0 0015 16.382V5.618a1 1 0 00-1.447-.894L9 7m0 13V7'],
    ],
    'الإنتاجية' => [
        ['label' => 'لوحة الإنتاجية', 'route' => 'productivity.dashboard', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z'],
        ['label' => 'الأهداف', 'route' => 'productivity.goals', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'المهام', 'route' => 'productivity.tasks', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
        ['label' => 'التقويم', 'route' => 'productivity.calendar', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'التذكيرات', 'route' => 'productivity.reminders', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        ['label' => 'الواجبات', 'route' => 'productivity.assignments.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => 'الامتحانات', 'route' => 'productivity.exams.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['label' => 'المشاريع', 'route' => 'productivity.projects.index', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
    ],
];

$isHome = request()->routeIs('home');
@endphp

<aside
    id="sidebar"
    class="sidebar rf-sidebar w-[280px] max-w-[85vw] flex flex-col
           fixed {{ $isHome ? 'top-[48px] md:top-[56px]' : $topOffset }} right-0 h-full z-[45]
           translate-x-full lg:translate-x-0
           transition-transform duration-300 ease-in-out
           lg:sticky lg:self-start lg:z-[45] lg:flex-shrink-0 lg:w-[240px] lg:max-w-none
           {{ $isHome ? 'lg:top-[56px] lg:h-[calc(100vh-56px)]' : 'lg:top-[' . $topOffsetDesktop . '] lg:h-[calc(100vh-' . str_replace('px', '', $topOffsetDesktop) . 'px)]' }}"
    role="navigation"
    aria-label="القائمة الجانبية"
>
    <div class="sidebar-logo-area">
        <div class="sidebar-logo-mark">ر</div>
        <div class="min-w-0">
            <p class="text-sm font-black leading-tight truncate text-text-primary">رفيق الطالب</p>
            <p class="text-[10px] leading-tight mt-0.5 text-text-muted">منصة نجاح الطالب</p>
        </div>
    </div>

    @if($showUser && $user)
    <div class="sidebar-user-area">
        <div class="user-avatar">
            {{ $userInitial ?? mb_substr($user->first_name ?? 'ط', 0, 1) }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[12.5px] font-bold truncate leading-tight text-text-primary">
                {{ $userName ?? ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') }}
            </p>
            <p class="text-[10px] mt-0.5 text-text-muted">{{ $userAcademicId ?? ($user->academic_id ?? '') }}</p>
        </div>
        <div class="status-pill">
            <div class="status-pill-dot"></div>
            نشط
        </div>
    </div>
    @endif

    <nav class="flex-1 px-3 py-2 overflow-y-auto no-scrollbar">
        @foreach($navItems as $section => $links)
            @if(is_string($section) && !is_numeric($section))
                <p class="nav-section-label">{{ $section }}</p>
            @endif
            @foreach($links as $link)
                @php
                    $isActive = request()->routeIs($link['route'] . '*');
                @endphp
                <a
                    href="{{ route($link['route']) }}"
                    class="nav-link {{ $isActive ? 'nav-link-active' : '' }}"
                    @if($isActive) aria-current="page" @endif
                    data-nav-link="{{ Str::slug($link['route']) }}"
                >
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                    </svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        @endforeach

        @if($variant === 'student')
            @php
                $isCareerActive = request()->routeIs('career.index');
                $isSkillsActive = request()->routeIs('skills.index');
            @endphp
            @if($isCareerActive || $isSkillsActive)
                <p class="nav-section-label">التطوير المهني والمهارات</p>
                <a href="{{ route('career.index') }}"
                   class="nav-link {{ $isCareerActive ? 'nav-link-active' : '' }}"
                   @if($isCareerActive) aria-current="page" @endif
                   data-nav-link="career-index">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    الملف المهني
                </a>
                <a href="{{ route('skills.index') }}"
                   class="nav-link {{ $isSkillsActive ? 'nav-link-active' : '' }}"
                   @if($isSkillsActive) aria-current="page" @endif
                   data-nav-link="skills-index">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    المهارات والشهادات
                </a>
            @endif
        @endif
    </nav>

    <div class="sidebar-bottom space-y-0.5">
        @if($showThemeToggle)
        <button type="button" onclick="toggleTheme()" class="nav-link w-full text-right">
            <svg class="h-4 w-4 shrink-0 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg class="h-4 w-4 shrink-0 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="dark:hidden">الوضع المظلم</span>
            <span class="hidden dark:inline">الوضع الفاتح</span>
        </button>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link nav-link-danger w-full text-right text-error">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                تسجيل الخروج
            </button>
        </form>
    </div>
</aside>
