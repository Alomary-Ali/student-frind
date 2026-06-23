@extends('layouts.dashboard')
@section('title', 'رفيق الطالب')

@push('styles')
<style>
    /* Header buttons: full width on mobile */
    @media (max-width: 540px) {
        .header-actions {
            width: 100%;
        }
        .header-actions a {
            flex: 1;
            justify-content: center;
        }
    }

    /* Unit card inner: respect RTL */
    .unit-card-inner {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
    }

    /* AI card buttons: full width on mobile */
    @media (max-width: 480px) {
        .ai-buttons a {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 p-4 md:p-6 lg:p-8">

    {{-- ─── Dashboard Header ─────────────────────────────────────────────── --}}
    <header class="flex flex-col sm:flex-row justify-between items-start gap-3 mb-6 animate-fade-in">
        {{-- Title block --}}
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-black leading-tight text-primary tracking-tight">
                رفيق الطالب
            </h1>
            <p class="text-[11px] sm:text-xs mt-1 leading-relaxed text-text-muted">
                منصة رفيق تدير رحلتك من أول محاضرة وحتى أول وظيفة.
            </p>
        </div>
        {{-- Action buttons --}}
        <div class="header-actions flex gap-2 w-full sm:w-auto">
            <a href="#"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg font-black text-[11px] transition-all hover:opacity-90 active:scale-95 bg-primary text-white min-h-9">
                <svg class="h-3.5 w-3.5 shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                تحليل AI
            </a>
            <a href="{{ route('academic.progress') }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg font-black text-[11px] transition-all border hover:bg-opacity-80 active:scale-95 border-border text-text-secondary bg-surface min-h-9">
                <svg class="h-3.5 w-3.5 shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
                الأداء
            </a>
        </div>
    </header>

    {{-- ─── 9-Unit System Grid ───────────────────────────────────────────── --}}
    <section class="mb-6 animate-fade-in-up" style="animation-delay:50ms;">
        {{-- Section header --}}
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-primary">
                وحدات النظام
            </h2>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black border bg-background border-border text-text-muted">
                9 وحدة
            </span>
        </div>

        @php
            $units = [
                ['name' => 'التخطيط الأكاديمي',  'desc' => 'المسار الدراسي',       'href' => route('academic.plan'),           'icon' => 'graduation'],
                ['name' => 'الإنتاجية اليومية',   'desc' => 'المهام والوقت',         'href' => route('productivity.dashboard'),  'icon' => 'list'],
                ['name' => 'التوجيه الذكي',        'desc' => 'تحليل AI',             'href' => '#',                              'icon' => 'brain'],
                ['name' => 'التطوير المهاري',      'desc' => 'خارطة المهارات',       'href' => route('skills.index'),            'icon' => 'zap'],
                ['name' => 'الهوية الرقمية',       'desc' => 'الملف المهني',          'href' => route('career.index'),            'icon' => 'shield'],
                ['name' => 'مركز الفرص',           'desc' => 'التدريب والتوظيف',     'href' => '#',                              'icon' => 'briefcase'],
                ['name' => 'المجتمع الجامعي',      'desc' => 'الإرشاد والتواصل',     'href' => '#',                              'icon' => 'sparkles'],
                ['name' => 'الذكاء المؤسسي',       'desc' => 'تحليلات الإدارة',      'href' => '#',                              'icon' => 'trending'],
                ['name' => 'إدارة المنصة',         'desc' => 'التكاملات',            'href' => '#',                              'icon' => 'activity'],
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
            @foreach($units as $i => $unit)
            <a href="{{ $unit['href'] }}"
               class="unit-card block relative overflow-hidden animate-fade-in-up"
               style="animation-delay:{{ ($i * 40) + 100 }}ms;">
                <div class="unit-card-inner">
                    {{-- Icon box --}}
                    <div class="unit-icon-box">
                        @if($unit['icon'] === 'graduation')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.5l9-4.5-9-4.5-9 4.5 9 4.5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20l-7-3.5V12l7 3.5L19 12v4.5L12 20z"/>
                            </svg>
                        @elseif($unit['icon'] === 'list')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        @elseif($unit['icon'] === 'brain')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        @elseif($unit['icon'] === 'zap')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        @elseif($unit['icon'] === 'shield')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        @elseif($unit['icon'] === 'briefcase')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        @elseif($unit['icon'] === 'sparkles')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        @elseif($unit['icon'] === 'trending')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        @elseif($unit['icon'] === 'activity')
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Text block --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[12px] sm:text-[13px] font-black leading-tight text-primary">
                            {{ $unit['name'] }}
                        </h3>
                        <p class="text-[10px] sm:text-[11px] leading-tight mt-0.5 text-text-muted">
                            {{ $unit['desc'] }}
                        </p>
                    </div>

                    {{-- Enter hint --}}
                    <div class="unit-enter mt-auto">
                        <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        دخول
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- ─── AI Insight Card ──────────────────────────────────────────────── --}}
    <section class="mb-6 animate-fade-in-up" style="animation-delay:500ms;">
        <div class="ai-card rounded-2xl overflow-hidden relative">

            {{-- Animated scanning line --}}
            <div class="ai-scan-line"></div>

            {{-- Decorative glows --}}
            <div class="ai-glow" style="top:-30px;right:-30px;animation:float 6s ease-in-out infinite;"></div>
            <div class="ai-glow bg-warning/14" style="bottom:-30px;left:-30px;animation:float 9s ease-in-out infinite reverse;"></div>
            <div class="ai-glow bg-primary/20" style="top:50%;left:40%;width:60px;height:60px;animation:float 7s ease-in-out infinite;animation-delay:2s;"></div>

            {{-- Gradient overlay --}}
            <div style="position:absolute;inset:0;background:linear-gradient(to left,hsl(var(--color-accent)/0.12),transparent 60%);opacity:0.6;pointer-events:none;"></div>

            <div class="relative z-10 p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">

                {{-- Content --}}
                <div class="text-right space-y-3 flex-1">

                    {{-- Badge with live status dot --}}
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border bg-surface/10 border-accent/30 text-accent">
                        {{-- Live pulsing dot --}}
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-accent"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-accent"></span>
                        </span>
                        مستشار ذاتي · مباشر
                    </div>

                    <h3 class="text-lg sm:text-xl font-black tracking-tight leading-tight text-white">
                        محرك رفيق الذكي
                    </h3>

                    <p class="text-[11px] sm:text-xs leading-relaxed max-w-xs text-surface/65">
                        تحليل لحظي لمخاطر التعثر ومطابقة المهارات مع احتياجات سوق العمل.
                    </p>

                    {{-- Action buttons --}}
                    <div class="ai-buttons flex flex-wrap gap-2 pt-1">
                        <a href="#"
                           class="ai-btn-primary inline-flex items-center justify-center gap-1.5 px-4 rounded-xl font-black text-[11px] text-white active:scale-95 h-9">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            بدء المحاكاة
                        </a>
                        <a href="#"
                           class="ai-btn-ghost inline-flex items-center justify-center gap-1.5 px-4 rounded-xl font-black text-[11px] text-white active:scale-95 h-9">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            التوصيات
                        </a>
                    </div>
                </div>

                {{-- AI Icon with ring pulse --}}
                <div class="ai-icon-wrap shrink-0 relative">
                    {{-- Outer ring --}}
                    <div class="ai-icon-ring"></div>
                    {{-- Glass box --}}
                    <div class="glass-container ai-icon-box rounded-2xl flex items-center justify-center relative z-10">
                        <svg class="h-9 w-9 sm:h-11 sm:w-11 text-accent"
                             style="animation:pulse-soft 3s ease-in-out infinite;"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
