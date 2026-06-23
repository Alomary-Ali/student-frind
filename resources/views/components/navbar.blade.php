@props([
    'navLinks' => [],
    'showNotifications' => true,
    'user' => null,
    'variant' => 'home', // 'home' | 'dashboard'
])

@php
    $user = $user ?: auth()->user();
    $initials = $user ? mb_substr($user->first_name ?? 'ط', 0, 1) : 'ط';
    $fullName = $user ? ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') : 'طالب رفيق';
    $email = $user ? ($user->email ?? '') : '';
@endphp

<nav class="navbar" dir="rtl">
    <div class="navbar-inner">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-logo">
            <div class="navbar-logo-mark">ر</div>
            <span class="navbar-logo-text">رفيق الطالب</span>
        </a>

        {{-- Desktop Nav Links (≥ 1024px, home only) --}}
        @if($variant === 'home' && count($navLinks) > 0)
        <div class="navbar-links">
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] ?? '#' }}"
                   class="navbar-link{{ isset($link['active']) && $link['active'] ? ' navbar-link-active' : '' }}">
                    @if(isset($link['icon']))
                        {!! $link['icon'] !!}
                    @endif
                    {{ $link['label'] }}
                </a>
            @endforeach

            @if($user && ($user->role === 'Admin' || $user->role === 'SuperAdmin'))
                <a href="#" class="navbar-link navbar-link-admin">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    الإدارة
                </a>
            @endif
        </div>
        @endif

        {{-- Breadcrumb / Page Title (dashboard only, desktop) --}}
        @if($variant === 'dashboard')
        <div class="hidden lg:flex items-center gap-2 flex-1 px-4 border-r border-border mr-3 pr-4">
            <svg class="h-3.5 w-3.5 shrink-0 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <a href="{{ route('home') }}" class="text-[11px] hover:underline text-text-muted transition-colors duration-150">الرئيسية</a>
            <svg class="h-3 w-3 shrink-0 text-border rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-[11px] font-bold text-text-primary">@yield('title', 'لوحة القيادة')</span>
        </div>
        @endif

        {{-- Right actions --}}
        <div class="navbar-actions">

            {{-- Mobile toggle --}}
            <button onclick="{{ $variant === 'dashboard' ? 'openSidebar()' : 'toggleSlideMenu()' }}" class="navbar-mobile-toggle" aria-label="القائمة">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Notifications --}}
            @if($showNotifications)
            <button class="navbar-notif" aria-label="الإشعارات">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="navbar-notif-dot"></span>
            </button>
            @endif

            {{-- AI Button (dashboard only) --}}
            @if($variant === 'dashboard')
            <button class="navbar-notif" style="background:linear-gradient(135deg,hsl(var(--color-primary)),hsl(var(--color-navy)));color:white;box-shadow:0 2px 8px hsl(var(--color-primary)/0.25);" aria-label="المساعد الذكي">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </button>
            @endif

            {{-- User avatar with dropdown --}}
            <div class="navbar-user" onclick="toggleUserDropdown(event)">
                <div class="navbar-avatar">{{ $initials }}</div>
                <svg class="navbar-avatar-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>

                {{-- Dropdown --}}
                <div id="userDropdown" class="navbar-dropdown">
                    <div class="navbar-dropdown-header">
                        <p class="navbar-dropdown-name">{{ $fullName }}</p>
                        <p class="navbar-dropdown-email">{{ $email }}</p>
                    </div>
                    <div class="navbar-dropdown-body">
                        <a href="{{ route('academic.profile') }}" class="navbar-dropdown-link">الملف الشخصي</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="navbar-dropdown-logout">تسجيل الخروج</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide Menu (mobile < 768px, home only) --}}
    @if($variant === 'home')
    <div id="slidemenuBackdrop" class="slidemenu-backdrop" onclick="closeSlideMenu()"></div>
    <aside id="slidemenu" class="slidemenu">
        <div class="slidemenu-header">
            <div class="slidemenu-avatar">{{ $initials }}</div>
            <div class="slidemenu-user-info">
                <p class="slidemenu-user-name">{{ $fullName }}</p>
                <p class="slidemenu-user-email">{{ $email }}</p>
            </div>
            <button onclick="closeSlideMenu()" class="slidemenu-close" aria-label="إغلاق">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @if(count($navLinks) > 0)
        <div class="slidemenu-body">
            <p class="slidemenu-section">الرئيسية</p>
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] ?? '#' }}"
                   class="slidemenu-link{{ isset($link['active']) && $link['active'] ? ' slidemenu-link-active' : '' }}">
                    @if(isset($link['icon']))
                        {!! $link['icon'] !!}
                    @else
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    @endif
                    {{ $link['label'] }}
                </a>
            @endforeach

            @if($user && ($user->role === 'Admin' || $user->role === 'SuperAdmin'))
                <p class="slidemenu-section">الإدارة</p>
                <a href="#" class="slidemenu-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    لوحة الإدارة
                </a>
            @endif
        </div>
        @endif

        <div class="slidemenu-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="slidemenu-logout">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>
    @endif
</nav>

<script>
    function toggleUserDropdown(e) {
        e.stopPropagation();
        const dd = document.getElementById('userDropdown');
        dd.classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        const dd = document.getElementById('userDropdown');
        if (dd && dd.classList.contains('show') && !e.target.closest('.navbar-user')) {
            dd.classList.remove('show');
        }
    });

    function toggleSlideMenu() {
        const menu = document.getElementById('slidemenu');
        const backdrop = document.getElementById('slidemenuBackdrop');
        const isOpen = menu.classList.contains('show');
        if (isOpen) {
            closeSlideMenu();
        } else {
            menu.classList.add('show');
            backdrop.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSlideMenu() {
        const menu = document.getElementById('slidemenu');
        const backdrop = document.getElementById('slidemenuBackdrop');
        menu.classList.remove('show');
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }
</script>
