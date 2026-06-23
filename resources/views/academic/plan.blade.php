@extends('layouts.dashboard')

@section('title', 'الخطة الأكاديمية')

@push('styles')
<style>
    .course-card { transition: all 0.2s ease; }
    .course-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px hsl(var(--color-primary) / 0.12); }
    .filter-btn.active { background: hsl(var(--color-primary)); color: white; border-color: hsl(var(--color-primary)); }
    .semester-section { scroll-margin-top: 80px; }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .status-dot-completed { background: hsl(var(--color-accent)); }
    .status-dot-in-progress { background: hsl(var(--color-primary)); }
    .status-dot-not-started { background: hsl(var(--color-text-muted)); }
    .status-dot-failed { background: hsl(var(--color-error)); }
    .status-dot-postponed { background: hsl(var(--color-warning)); }

    .course-card-base {
        background: hsl(var(--color-surface));
        border: 1px solid hsl(var(--color-border));
    }

    .badge-completed { background: hsl(var(--color-accent)/0.10); color: hsl(var(--color-accent)); }
    .badge-in-progress { background: hsl(var(--color-primary)/0.10); color: hsl(var(--color-primary)); }
    .badge-failed { background: hsl(var(--color-error)/0.10); color: hsl(var(--color-error)); }
    .badge-postponed { background: hsl(var(--color-warning)/0.10); color: hsl(var(--color-warning)); }
    .badge-not-started { background: hsl(var(--color-text-muted)/0.10); color: hsl(var(--color-text-muted)); }
</style>
@endpush

@section('content')

@if($error)
    <div class="rounded-2xl p-6 mb-6 bg-error/10 border border-error/20">
        <p class="font-semibold text-error">{{ $error }}</p>
    </div>
@elseif(!$profile)
    <div class="rounded-2xl p-16 text-center mb-6 bg-background border border-border">
        <div class="w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center bg-border">
            <svg class="h-10 w-10 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا يوجد ملف أكاديمي</p>
        <p class="text-sm text-text-secondary">يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي</p>
    </div>
@else
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">الخطة الأكاديمية التفاعلية</h1>
        <p class="text-sm mt-0.5 text-text-secondary">عرض جميع مقررات التخصص مرتبة حسب المستوى والفصل الدراسي</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-text-muted">{{ count($courses ?? []) }} مقرر</span>
    </div>
</div>

{{-- Profile Summary Strip --}}
<div class="p-4 rounded-xl border border-border bg-surface shadow-sm mb-6">
    <div class="flex flex-wrap items-center gap-4 text-sm">
        <div class="flex items-center gap-2">
            <span class="text-text-muted">الطالب:</span>
            <span class="font-bold text-text-primary">{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</span>
        </div>
        <div class="hidden sm:block h-6 w-px bg-border"></div>
        <div class="flex items-center gap-2">
            <span class="text-text-muted">GPA:</span>
            <span class="font-bold text-text-primary">{{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}</span>
        </div>
        <div class="hidden sm:block h-6 w-px bg-border"></div>
        <div class="flex items-center gap-2">
            <span class="text-text-muted">المستوى:</span>
            <span class="font-bold text-text-primary">{{ $profile['level'] ?? '1' }}</span>
        </div>
        <div class="hidden sm:block h-6 w-px bg-border"></div>
        <div class="flex items-center gap-2">
            <span class="text-text-muted">الساعات:</span>
            <span class="font-bold text-text-primary">{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }}/{{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }}</span>
        </div>
        <div class="hidden sm:block h-6 w-px bg-border"></div>
        <div class="flex items-center gap-2">
            <span class="text-text-muted">تاريخ التخرج:</span>
            <span class="font-bold text-text-primary">{{ $graduationProgress && $graduationProgress['estimated_graduation_date'] ? date('F Y', strtotime($graduationProgress['estimated_graduation_date'])) : 'غير محدد' }}</span>
        </div>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="flex flex-wrap items-center gap-2 mb-6" id="filter-tabs">
    <button class="filter-btn active px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-border bg-surface text-text-primary" data-filter="all">
        جميع المقررات
    </button>
    <button class="filter-btn px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-border bg-surface text-text-primary" data-filter="completed">
        <span class="status-dot status-dot-completed ml-1.5"></span>مكتمل
    </button>
    <button class="filter-btn px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-border bg-surface text-text-primary" data-filter="in_progress">
        <span class="status-dot status-dot-in-progress ml-1.5"></span>قيد التقدم
    </button>
    <button class="filter-btn px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-border bg-surface text-text-primary" data-filter="not_started">
        <span class="status-dot status-dot-not-started ml-1.5"></span>غير مسجل
    </button>
    <button class="filter-btn px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-border bg-surface text-text-primary" data-filter="failed">
        <span class="status-dot status-dot-failed ml-1.5"></span>راسب
    </button>
</div>

{{-- Progress Bar Summary --}}
<div class="p-5 rounded-xl border border-border bg-surface shadow-sm mb-6">
    @php
        $total = count($courses ?? []);
        $completed = count(array_filter($courses ?? [], fn($c) => $c['status'] === 'completed'));
        $inProgress = count(array_filter($courses ?? [], fn($c) => $c['status'] === 'in_progress'));
        $notStarted = count(array_filter($courses ?? [], fn($c) => $c['status'] === 'not_started'));
        $failed = count(array_filter($courses ?? [], fn($c) => $c['status'] === 'failed'));
        $pct = $total > 0 ? round($completed / $total * 100) : 0;
    @endphp
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="flex-1 w-full">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-semibold text-text-secondary">إنجاز الخطة الدراسية</span>
                <span class="text-xs font-bold text-text-primary">{{ $completed }}/{{ $total }} مقرر ({{ $pct }}%)</span>
            </div>
            <div class="h-1.5 bg-border rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width:{{ $pct }}%;"></div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs shrink-0">
            <span class="flex items-center gap-1"><span class="status-dot status-dot-completed"></span> {{ $completed }} مكتمل</span>
            <span class="flex items-center gap-1"><span class="status-dot status-dot-in-progress"></span> {{ $inProgress }} قيد التقدم</span>
            <span class="flex items-center gap-1"><span class="status-dot status-dot-not-started"></span> {{ $notStarted }} غير مسجل</span>
            @if($failed > 0)
            <span class="flex items-center gap-1"><span class="status-dot status-dot-failed"></span> {{ $failed }} راسب</span>
            @endif
        </div>
    </div>
</div>

{{-- Study Plan by Semester --}}
<div class="space-y-6">
    @forelse($groupedCourses ?? [] as $semesterLabel => $semesterCourses)
    <div class="semester-section p-5 rounded-xl border border-border bg-surface shadow-sm" data-semester="{{ $semesterLabel }}">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-text-primary">{{ $semesterLabel }}</h2>
            <span class="text-xs text-text-muted">{{ count($semesterCourses) }} مقررات</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($semesterCourses as $course)
            <div class="course-card p-4 rounded-xl cursor-pointer course-card-base flex flex-col justify-between h-full"
                data-status="{{ $course['status'] }}"
                data-code="{{ $course['code'] }}">

                <div>
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="h-10 w-10 rounded-lg flex items-center justify-center font-bold text-xs bg-surface border border-border text-primary shrink-0">
                            {{ substr($course['code'], -4) }}
                        </div>
                        @switch($course['status'])
                            @case('completed')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-completed shrink-0">مكتمل</span>
                                @break
                            @case('in_progress')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-in-progress shrink-0">قيد التقدم</span>
                                @break
                            @case('failed')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-failed shrink-0">راسب</span>
                                @break
                            @case('postponed')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-postponed shrink-0">مؤجل</span>
                                @break
                            @default
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-not-started shrink-0">غير مسجل</span>
                        @endswitch
                    </div>

                    <h3 class="text-sm font-bold mb-0.5 text-text-primary">{{ $course['name'] }}</h3>
                    <p class="text-[11px] mb-3 text-text-secondary">{{ $course['code'] }} · {{ $course['credit_hours'] }} ساعة</p>
                </div>

                <div class="flex items-center gap-2 mt-auto pt-2">
                    @if($course['is_required'])
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold" style="background:hsl(var(--color-primary)/0.05);color:hsl(var(--color-primary));">إلزامي</span>
                    @else
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold badge-not-started">اختياري</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="rounded-2xl p-16 text-center bg-surface border border-border">
        <svg class="h-12 w-12 mx-auto mb-4 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد خطة دراسية</p>
        <p class="text-sm text-text-secondary">لم يتم تعيين خطة دراسية بعد. يرجى التواصل مع الإدارة.</p>
    </div>
    @endforelse
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const courseCards = document.querySelectorAll('.course-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                courseCards.forEach(card => {
                    const status = card.dataset.status;
                    if (filter === 'all' || status === filter) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                document.querySelectorAll('.semester-section').forEach(section => {
                    const cards = section.querySelectorAll('.course-card');
                    let hasVisible = false;
                    cards.forEach(card => {
                        if (card.style.display !== 'none') {
                            hasVisible = true;
                        }
                    });
                    if (hasVisible) {
                        section.style.display = '';
                    } else {
                        section.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush
