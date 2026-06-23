@extends('layouts.dashboard')
@section('title', 'لوحة التحكم الإنتاجية')
@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" data-observe-section="productivity-dashboard">
    @php
    $stats = [
        ['label'=>'الأهداف النشطة',  'value'=>$dashboard->activeGoals,   'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',  'variant'=>'primary','sub'=>'هدف نشط'],
        ['label'=>'المهام المعلقة',  'value'=>$dashboard->pendingTasks,  'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'variant'=>'warning','sub'=>'مهمة معلقة'],
        ['label'=>'المهام المكتملة', 'value'=>$dashboard->completedTasks,'icon'=>'M5 13l4 4L19 7',                                   'variant'=>'accent','sub'=>'مهمة مكتملة'],
        ['label'=>'نسبة الإنجاز',    'value'=>number_format($dashboard->overallCompletionRate,1).'%','icon'=>'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6','variant'=>'primary','sub'=>'إنجاز كلي'],
    ];

    $iconVariants = ['primary' => 'bg-primary/10 text-primary', 'accent' => 'bg-accent/10 text-accent', 'warning' => 'bg-warning/10 text-warning', 'error' => 'bg-error/10 text-error'];
    @endphp
    @foreach($stats as $i => $s)
    <x-rf-card variant="default" class="animate-fade-in-up" style="animation-delay:{{ $i * 50 }}ms">
        <div class="flex items-start justify-between mb-3">
            <div class="inline-flex items-center justify-center rounded-xl flex-shrink-0 w-10 h-10 {{ $iconVariants[$s['variant']] }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
        </div>
        <p class="stat-number">{{ $s['value'] }}</p>
        <p class="text-xs mt-1.5 font-medium text-text-muted">{{ $s['sub'] }}</p>
        <p class="section-label mt-2">{{ $s['label'] }}</p>
    </x-rf-card>
    @endforeach
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6" data-observe-section="productivity-dashboard">
    <x-rf-card variant="default" class="animate-fade-in-up delay-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="heading-premium text-lg">توزيع المهام</p>
                <p class="text-xs mt-0.5 text-text-muted">حسب الحالة</p>
            </div>
        </div>
        <div class="h-48 relative">
            <canvas id="taskStatusChart"></canvas>
        </div>
    </x-rf-card>
    <x-rf-card variant="default" class="animate-fade-in-up delay-150">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="heading-premium text-lg">نسبة الإنجاز</p>
                <p class="text-xs mt-0.5 text-text-muted">المهام المكتملة مقابل الكلي</p>
            </div>
        </div>
        <div class="h-48 relative">
            <canvas id="completionChart"></canvas>
        </div>
    </x-rf-card>
</div>

{{-- Recent Tasks --}}
<x-rf-card variant="default" class="animate-fade-in-up delay-150" data-observe-section="productivity-dashboard">
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="heading-premium text-lg">المهام الأخيرة</p>
            <p class="text-xs mt-0.5 text-text-muted">آخر 5 مهام</p>
        </div>
        <a href="{{ route('productivity.tasks') }}" class="text-[12px] font-bold hover:text-accent transition-colors text-primary">عرض الكل ←</a>
    </div>

    @if(count($dashboard->recentTasks) > 0)
        <div class="space-y-2.5">
            @foreach($dashboard->recentTasks as $task)
            <div class="interactive-row flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 rounded-xl border border-transparent transition-all gap-3 bg-background">
                <div class="flex items-center gap-3">
                    @php
                    $taskIconVariant = $task->status === 'completed' ? 'accent' : ($task->priority === 'urgent' ? 'error' : 'primary');
                    @endphp
                    <div class="inline-flex items-center justify-center rounded-xl flex-shrink-0 w-8 h-8 {{ $iconVariants[$taskIconVariant] }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            @if($task->status === 'completed')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold">{{ $task->title }}</p>
                        @if($task->dueDate)
                            <p class="text-xs mt-0.5 text-text-muted">{{ $task->dueDate }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($task->priority === 'urgent')
                        <x-rf-badge variant="error" size="sm">عاجل</x-rf-badge>
                    @elseif($task->priority === 'high')
                        <x-rf-badge variant="primary" size="sm">مرتفع</x-rf-badge>
                    @else
                        <x-rf-badge variant="muted" size="sm">عادي</x-rf-badge>
                    @endif
                    @if($task->status === 'completed')
                        <x-rf-badge variant="accent" size="sm">مكتمل</x-rf-badge>
                    @elseif($task->status === 'in_progress')
                        <x-rf-badge variant="primary" size="sm">قيد التنفيذ</x-rf-badge>
                    @else
                        <x-rf-badge variant="muted" size="sm">معلق</x-rf-badge>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-14">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-border">
                <svg class="h-8 w-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <p class="text-sm font-bold mb-1">لا توجد مهام بعد</p>
            <p class="text-xs mb-5 text-text-muted">ابدأ بإنشاء مهمتك الأولى</p>
            <a href="{{ route('productivity.tasks.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-colors shadow-md bg-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                إنشاء مهمة
            </a>
        </div>
    @endif
</x-rf-card>

{{-- Upcoming Events --}}
<x-rf-card variant="default" class="animate-fade-in-up delay-200" data-observe-section="productivity-dashboard">
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="heading-premium text-lg">الأحداث القادمة</p>
            <p class="text-xs mt-0.5 text-text-muted">من التقويم</p>
        </div>
        <a href="{{ route('productivity.calendar') }}" class="text-[12px] font-bold hover:text-accent transition-colors text-primary">عرض التقويم ←</a>
    </div>

    @if(count($dashboard->upcomingEvents) > 0)
        <div class="space-y-2.5">
            @foreach($dashboard->upcomingEvents as $event)
            <div class="interactive-row flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 rounded-xl border border-transparent transition-all gap-3 bg-background">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center rounded-xl flex-shrink-0 w-10 h-10 bg-primary/10 text-primary">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold">{{ $event->title }}</p>
                        <p class="text-xs mt-0.5 text-text-muted">{{ $event->startsAt }} — {{ $event->endsAt }}</p>
                    </div>
                </div>
                <x-rf-badge variant="{{ $event->isAllDay ? 'primary' : 'muted' }}">
                    {{ $event->isAllDay ? 'كامل اليوم' : 'محدد الوقت' }}
                </x-rf-badge>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-14">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-border">
                <svg class="h-8 w-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-bold mb-1">لا توجد أحداث قادمة</p>
            <p class="text-xs mb-5 text-text-muted">أضف حدثاً لتقويمك</p>
            <a href="{{ route('productivity.calendar.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-colors shadow-md bg-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                إضافة حدث
            </a>
        </div>
    @endif
</x-rf-card>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Resolve a CSS custom property (space-separated HSL like "225 55% 31%")
     * into a Canvas-compatible color string.
     */
    function resolveColor(varName, alpha = null) {
        const raw = getComputedStyle(document.documentElement)
            .getPropertyValue(varName)
            .trim();
        if (!raw) return alpha !== null ? `rgba(0,0,0,${alpha})` : 'rgb(0,0,0)';
        const parts = raw.split(/\s+/);
        const h = parts[0] ?? '0';
        const s = parts[1] ?? '0%';
        const l = parts[2] ?? '0%';
        return alpha !== null
            ? `hsla(${h}, ${s}, ${l}, ${alpha})`
            : `hsl(${h}, ${s}, ${l})`;
    }

    const textColor = resolveColor('--color-text-muted');
    const gridColor = resolveColor('--color-border');

    const totalTasks = {{ $dashboard->pendingTasks + $dashboard->inProgressTasks + $dashboard->completedTasks + $dashboard->overdueTasks }};
    const pending = {{ $dashboard->pendingTasks }};
    const inProgress = {{ $dashboard->inProgressTasks }};
    const completed = {{ $dashboard->completedTasks }};
    const overdue = {{ $dashboard->overdueTasks }};

    // Task Status Donut
    const taskCtx = document.getElementById('taskStatusChart');
    if (taskCtx) {
        new Chart(taskCtx, {
            type: 'doughnut',
            data: {
                labels: ['معلق', 'قيد التنفيذ', 'مكتمل', 'متأخر'],
                datasets: [{
                    data: [pending, inProgress, completed, overdue],
                    backgroundColor: [
                        resolveColor('--color-warning'),
                        resolveColor('--color-primary'),
                        resolveColor('--color-accent'),
                        resolveColor('--color-error')
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: { color: textColor, font: { family: 'Cairo', size: 10 }, padding: 12 }
                    }
                }
            }
        });
    }

    // Completion Rate Doughnut
    const compCtx = document.getElementById('completionChart');
    if (compCtx) {
        const compRate = {{ $dashboard->overallCompletionRate }};
        new Chart(compCtx, {
            type: 'doughnut',
            data: {
                labels: ['منجز', 'متبقي'],
                datasets: [{
                    data: [compRate, 100 - compRate],
                    backgroundColor: [
                        resolveColor('--color-accent'),
                        resolveColor('--color-border')
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: { color: textColor, font: { family: 'Cairo', size: 10 }, padding: 12 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
