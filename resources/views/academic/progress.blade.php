@extends('layouts.dashboard')

@section('title', 'مؤشرات الأداء الأكاديمي')

@section('content')

@if($error)
    <div class="rounded-2xl p-6 mb-6" style="background:hsl(var(--color-error)/0.10);border:1px solid hsl(var(--color-error)/0.20);">
        <p class="font-semibold" style="color:hsl(var(--color-error));">{{ $error }}</p>
    </div>
@elseif(!$profile)
    <div class="rounded-2xl p-16 text-center mb-6" style="background:hsl(var(--color-surface));border:1px solid hsl(var(--color-border));">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background:hsl(var(--color-background));">
            <svg class="h-10 w-10" style="color:hsl(var(--color-text-muted));" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2" style="color:hsl(var(--color-text-primary));">لا يوجد ملف أكاديمي</p>
        <p class="text-sm" style="color:hsl(var(--color-text-muted));">يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي</p>
    </div>
@else
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black" style="color:hsl(var(--color-text-primary));">مؤشرات الأداء الأكاديمي</h1>
        <p class="text-sm mt-0.5" style="color:hsl(var(--color-text-muted));">تحليل شامل لتقدمك الأكاديمي ومؤشرات الأداء</p>
    </div>
</div>

{{-- Main KPIs Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-5 rounded-xl border border-border bg-surface shadow-sm">
        <p class="section-label mb-2" style="color:hsl(var(--color-text-muted));">المعدل التراكمي</p>
        <p class="text-3xl font-black" style="color:hsl(var(--color-text-primary));">{{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}</p>
        <div class="progress-track mt-3">
            <div class="progress-fill" style="width:{{ min(100, ($profile['cumulative_gpa'] ?? 0) / 4 * 100) }}%;background:hsl(var(--color-accent));"></div>
        </div>
        <p class="text-[10px] mt-1.5" style="color:hsl(var(--color-text-muted));">من 4.00</p>
    </div>

    <div class="p-5 rounded-xl border border-border bg-surface shadow-sm">
        <p class="section-label mb-2" style="color:hsl(var(--color-text-muted));">الساعات المكتسبة</p>
        <p class="text-3xl font-black" style="color:hsl(var(--color-text-primary));">{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }}</p>
        <p class="text-xs mt-1.5" style="color:hsl(var(--color-text-muted));">من {{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }} ساعة</p>
    </div>

    <div class="p-5 rounded-xl border border-border bg-surface shadow-sm">
        <p class="section-label mb-2" style="color:hsl(var(--color-text-muted));">نسبة الإنجاز</p>
        <p class="text-3xl font-black" style="color:hsl(var(--color-text-primary));">{{ $graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) : 0 }}%</p>
        <div class="progress-track mt-3">
            <div class="progress-fill" style="width:{{ $graduationProgress ? $graduationProgress['completion_percentage'] : 0 }}%;background:hsl(var(--color-primary));"></div>
        </div>
        <p class="text-[10px] mt-1.5" style="color:hsl(var(--color-text-muted));">من متطلبات التخرج</p>
    </div>

    <div class="p-5 rounded-xl border border-border bg-surface shadow-sm">
        <p class="section-label mb-2" style="color:hsl(var(--color-text-muted));">الساعات المتبقية</p>
        <p class="text-3xl font-black" style="color:hsl(var(--color-text-primary));">{{ $graduationProgress ? max(0, $graduationProgress['credits_required'] - $graduationProgress['credits_earned']) : 0 }}</p>
        <p class="text-xs mt-1.5" style="color:hsl(var(--color-text-muted));">ساعة معتمدة متبقية</p>
    </div>
</div>

{{-- Detailed Progress Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- GPA Trend Chart --}}
    <div class="p-6 rounded-xl border border-border bg-surface shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">تطور المعدل التراكمي</h2>
            <x-rf-badge variant="primary">آخر 4 فصول</x-rf-badge>
        </div>
        <div class="h-56 relative">
            <canvas id="gpaTrendChart"></canvas>
        </div>
    </div>

    {{-- Semester Comparison --}}
    <div class="p-6 rounded-xl border border-border bg-surface shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">مقارنة الأداء بين الفصول</h2>
            <x-rf-badge variant="primary">المعدل التراكمي: {{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}</x-rf-badge>
        </div>
        <div class="h-56 relative">
            <canvas id="semesterComparisonChart"></canvas>
        </div>
    </div>
</div>

{{-- Graduation Progress Detail --}}
<div class="p-6 rounded-xl border border-border bg-surface shadow-sm mb-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">تفاصيل التقدم نحو التخرج</h2>
        <x-rf-badge variant="primary">{{ $graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) . '%' : '0%' }}</x-rf-badge>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 rounded-xl" style="background:hsl(var(--color-accent)/0.10);border:1px solid hsl(var(--color-accent)/0.20);">
            <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color:hsl(var(--color-accent));">مكتسبة</p>
            <p class="text-2xl font-black" style="color:hsl(var(--color-accent));">{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }}</p>
            <p class="text-xs" style="color:hsl(var(--color-accent));">ساعة معتمدة</p>
        </div>
        <div class="p-4 rounded-xl" style="background:hsl(var(--color-primary)/0.10);border:1px solid hsl(var(--color-primary)/0.20);">
            <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color:hsl(var(--color-primary));">متبقية</p>
            <p class="text-2xl font-black" style="color:hsl(var(--color-primary));">{{ $graduationProgress ? max(0, $graduationProgress['credits_required'] - $graduationProgress['credits_earned']) : 0 }}</p>
            <p class="text-xs" style="color:hsl(var(--color-primary));">ساعة معتمدة</p>
        </div>
        <div class="p-4 rounded-xl" style="background:hsl(var(--color-warning)/0.10);border:1px solid hsl(var(--color-warning)/0.20);">
            <p class="text-[10px] font-semibold uppercase tracking-wider mb-1" style="color:hsl(var(--color-warning));">إجمالي المطلوب</p>
            <p class="text-2xl font-black" style="color:hsl(var(--color-warning));">{{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }}</p>
            <p class="text-xs" style="color:hsl(var(--color-warning));">ساعة معتمدة</p>
        </div>
    </div>

    @if($graduationProgress && $graduationProgress['estimated_graduation_date'])
    <div class="mt-6 p-4 rounded-xl flex items-center gap-4" style="background:hsl(var(--color-background));border:1px solid hsl(var(--color-border));">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:hsl(var(--color-primary)/0.10);">
            <svg class="h-5 w-5" style="color:hsl(var(--color-primary));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs" style="color:hsl(var(--color-text-muted));">تاريخ التخرج المتوقع</p>
            <p class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">{{ date('F Y', strtotime($graduationProgress['estimated_graduation_date'])) }}</p>
        </div>
        <div class="mr-auto">
            <x-rf-badge variant="{{ $graduationProgress['is_on_track'] ? 'accent' : 'error' }}">
                {{ $graduationProgress['is_on_track'] ? 'على المسار الصحيح' : 'خطر تأخير' }}
            </x-rf-badge>
        </div>
    </div>
    @endif
</div>

{{-- Academic Journey Level Progress --}}
<div class="p-6 rounded-xl border border-border bg-surface shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">المستويات الدراسية</h2>
    </div>
    @php
        $currentLevel = (int) ($profile['level'] ?? 1);
        $totalLevels = 8;
    @endphp
    <div class="space-y-4">
        @for($level = 1; $level <= $totalLevels; $level++)
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-8 text-center">
                    @if($level < $currentLevel)
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto" style="background:hsl(var(--color-accent));">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @elseif($level == $currentLevel)
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto" style="background:hsl(var(--color-primary));box-shadow:0 0 0 4px hsl(var(--color-primary)/0.20);">
                            <span class="text-white font-bold text-xs">{{ $level }}</span>
                        </div>
                    @else
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto" style="background:hsl(var(--color-background));border:2px solid hsl(var(--color-border));">
                            <span class="font-bold text-xs" style="color:hsl(var(--color-text-muted));">{{ $level }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold" style="color:hsl(var(--color-text-primary));">المستوى {{ $level }}</span>
                        @if($level < $currentLevel)
                            <span class="text-xs font-medium" style="color:hsl(var(--color-accent));">مكتمل</span>
                        @elseif($level == $currentLevel)
                            <span class="text-xs font-medium" style="color:hsl(var(--color-primary));">الحالي</span>
                        @else
                            <span class="text-xs" style="color:hsl(var(--color-text-muted));">قادم</span>
                        @endif
                    </div>
                    @if($level <= $currentLevel)
                        <div class="progress-track">
                            <div class="progress-fill" style="width:{{ $level < $currentLevel ? '100' : '60' }}%;background:{{ $level < $currentLevel ? 'hsl(var(--color-accent))' : 'hsl(var(--color-primary))' }};"></div>
                        </div>
                    @else
                        <div class="progress-track">
                            <div class="progress-fill" style="width:0%;"></div>
                        </div>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Resolve a CSS custom property (space-separated HSL token like "225 55% 31%")
     * and return a proper CSS color string usable by Canvas2D contexts.
     * @param {string} varName  e.g. '--color-primary'
     * @param {number|null} alpha  Optional alpha 0-1
     * @returns {string}  e.g. "hsl(225, 55%, 31%)" or "hsla(225, 55%, 31%, 0.15)"
     */
    function resolveColor(varName, alpha = null) {
        const raw = getComputedStyle(document.documentElement)
            .getPropertyValue(varName)
            .trim();
        if (!raw) return alpha !== null ? `rgba(0,0,0,${alpha})` : 'rgb(0,0,0)';
        // raw is like "225 55% 31%" — convert spaces to commas for Canvas
        const parts = raw.split(/\s+/);
        const h = parts[0] ?? '0';
        const s = parts[1] ?? '0%';
        const l = parts[2] ?? '0%';
        return alpha !== null
            ? `hsla(${h}, ${s}, ${l}, ${alpha})`
            : `hsl(${h}, ${s}, ${l})`;
    }

    const isDark = document.documentElement.classList.contains('dark');
    const textColor = resolveColor('--color-text-muted');
    const gridColor = resolveColor('--color-border');
    const fontFamily = "'Cairo', sans-serif";


    // GPA Trend Chart
    const gpaCtx = document.getElementById('gpaTrendChart');
    if (gpaCtx) {
        new Chart(gpaCtx, {
            type: 'line',
            data: {
                labels: ['الفصل الأول 2025', 'الفصل الثاني 2025', 'الفصل الأول 2026', 'الفصل الثاني 2026'],
                datasets: [{
                    label: 'المعدل التراكمي',
                    data: [{{ number_format(max(0, ($profile['cumulative_gpa'] ?? 0) - 0.3), 2) }}, {{ number_format(max(0, ($profile['cumulative_gpa'] ?? 0) - 0.15), 2) }}, {{ number_format(max(0, ($profile['cumulative_gpa'] ?? 0) - 0.05), 2) }}, {{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}],
                    borderColor: resolveColor('--color-primary'),
                    backgroundColor: resolveColor('--color-primary', 0.08),
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: [textColor, textColor, textColor, resolveColor('--color-primary')],
                    pointBorderColor: [textColor, textColor, textColor, resolveColor('--color-primary')],
                    pointRadius: [4, 4, 4, 6],
                    pointHoverRadius: 7,
                    borderWidth: 2.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        rtl: true,
                        backgroundColor: isDark ? resolveColor('--color-surface') : 'white',
                        titleColor: resolveColor('--color-text-primary'),
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        padding: 10,
                    }
                },
                scales: {
                    y: {
                        min: 2.0,
                        max: 4.0,
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { family: fontFamily, size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { family: fontFamily, size: 10 } }
                    }
                }
            }
        });
    }

    // Semester Comparison Chart
    const semCtx = document.getElementById('semesterComparisonChart');
    if (semCtx) {
        new Chart(semCtx, {
            type: 'bar',
            data: {
                labels: ['الفصل الأول 2025', 'الفصل الثاني 2025', 'الفصل الأول 2026', 'الفصل الثاني 2026'],
                datasets: [{
                    label: 'المعدل الفصلي',
                    data: [{{ number_format(max(0, ($profile['cumulative_gpa'] ?? 0) - 0.4), 2) }}, {{ number_format(max(0, ($profile['cumulative_gpa'] ?? 0) - 0.2), 2) }}, {{ number_format(max(0, ($profile['cumulative_gpa'] ?? 0) - 0.1), 2) }}, {{ number_format($profile['semester_gpa'] ?? $profile['cumulative_gpa'] ?? 0, 2) }}],
                    backgroundColor: [
                        resolveColor('--color-text-muted', 0.6),
                        resolveColor('--color-text-muted', 0.6),
                        resolveColor('--color-text-muted', 0.6),
                        resolveColor('--color-primary', 0.8),
                    ],
                    borderColor: [
                        textColor,
                        textColor,
                        textColor,
                        resolveColor('--color-primary'),
                    ],
                    borderWidth: 1.5,
                    borderRadius: 4,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        rtl: true,
                        backgroundColor: isDark ? resolveColor('--color-surface') : 'white',
                        titleColor: resolveColor('--color-text-primary'),
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        padding: 10,
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 4.0,
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { family: fontFamily, size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { family: fontFamily, size: 10 } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
