@extends('layouts.dashboard')

@section('title', 'خريطة التخرج')

@push('styles')
<style>
    .timeline-dot { width: 14px; height: 14px; border-radius: 50%; flex-shrink: 0; position: relative; z-index: 1; }
    .timeline-line { width: 2px; flex-shrink: 0; margin: 0 auto; }
    .timeline-item:last-child .timeline-line { display: none; }
    .glow-dot { box-shadow: 0 0 0 4px hsl(var(--color-primary)/0.15); }
</style>
@endpush

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
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-black" style="color:hsl(var(--color-text-primary));">خريطة التخرج</h1>
            <p class="text-sm mt-0.5" style="color:hsl(var(--color-text-muted));">تصور بصري لمسارك الأكاديمي حتى التخرج</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-left">
                <p class="text-[10px]" style="color:hsl(var(--color-text-muted));">تاريخ التخرج المتوقع</p>
                <p class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">{{ $graduationProgress && $graduationProgress['estimated_graduation_date'] ? date('F Y', strtotime($graduationProgress['estimated_graduation_date'])) : 'غير محدد' }}</p>
            </div>
        </div>
    </div>

    {{-- Main Progress Card --}}
    <div class="p-6 rounded-xl border border-border bg-surface shadow-sm mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">نسبة الإنجاز الكلية</h2>
            <span class="text-2xl font-black" style="color:hsl(var(--color-text-primary));">{{ $graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) : 0 }}%</span>
        </div>
        <div class="progress-track mb-2" style="height: 14px;">
            <div class="progress-fill rounded-full" style="width: {{ $graduationProgress ? $graduationProgress['completion_percentage'] : 0 }}%;background:linear-gradient(90deg,hsl(var(--color-primary)),hsl(var(--color-accent)));"></div>
        </div>
        <div class="flex items-center justify-between text-xs" style="color:hsl(var(--color-text-muted));">
            <span>{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }} ساعة مكتسبة</span>
            <span>{{ $graduationProgress ? max(0, $graduationProgress['credits_required'] - $graduationProgress['credits_earned']) : 0 }} ساعة متبقية</span>
            <span>{{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }} ساعة إجمالي</span>
        </div>
        <div class="flex items-center gap-2 mt-4">
            <x-rf-badge variant="{{ $graduationProgress && $graduationProgress['is_on_track'] ? 'accent' : 'error' }}">
                {{ $graduationProgress && $graduationProgress['is_on_track'] ? 'على المسار الصحيح' : 'خطر تأخير' }}
            </x-rf-badge>
            <span class="text-xs" style="color:hsl(var(--color-text-muted));">المعدل التراكمي: {{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}</span>
        </div>
    </div>

    {{-- Credits Breakdown --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-5 rounded-xl text-center" style="background:hsl(var(--color-accent)/0.10);border:1px solid hsl(var(--color-accent)/0.20);">
            <p class="text-3xl font-black" style="color:hsl(var(--color-accent));">{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }}</p>
            <p class="text-xs font-semibold mt-1" style="color:hsl(var(--color-accent));">ساعة مكتسبة</p>
            <p class="text-[10px] mt-0.5" style="color:hsl(var(--color-accent)/0.60);">مكتملة</p>
        </div>
        <div class="p-5 rounded-xl text-center" style="background:hsl(var(--color-warning)/0.10);border:1px solid hsl(var(--color-warning)/0.20);">
            <p class="text-3xl font-black" style="color:hsl(var(--color-warning));">{{ $graduationProgress ? max(0, $graduationProgress['credits_required'] - $graduationProgress['credits_earned']) : 0 }}</p>
            <p class="text-xs font-semibold mt-1" style="color:hsl(var(--color-warning));">ساعة متبقية</p>
            <p class="text-[10px] mt-0.5" style="color:hsl(var(--color-warning)/0.60);">مطلوبة</p>
        </div>
        <div class="p-5 rounded-xl text-center" style="background:hsl(var(--color-primary)/0.10);border:1px solid hsl(var(--color-primary)/0.20);">
            <p class="text-3xl font-black" style="color:hsl(var(--color-primary));">{{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }}</p>
            <p class="text-xs font-semibold mt-1" style="color:hsl(var(--color-primary));">إجمالي الساعات</p>
            <p class="text-[10px] mt-0.5" style="color:hsl(var(--color-primary)/0.60);">متطلبات التخرج</p>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="p-6 rounded-xl border border-border bg-surface shadow-sm">
        <h2 class="text-sm font-bold mb-6" style="color:hsl(var(--color-text-primary));">المسار الأكاديمي</h2>

        @php
            $currentLevel = (int) ($profile['level'] ?? 1);
            $totalLevels = 8;
            $creditsPerLevel = $graduationProgress ? round($graduationProgress['credits_required'] / $totalLevels) : 16;
        @endphp

        <div class="space-y-0">
            @for($level = 1; $level <= $totalLevels; $level++)
            <div class="timeline-item flex items-start gap-4">
                <div class="flex flex-col items-center">
                    @if($level < $currentLevel)
                        <div class="timeline-dot" style="background:hsl(var(--color-accent));border:2px solid hsl(var(--color-surface));box-shadow:0 1px 3px hsl(var(--color-primary)/0.06);"></div>
                    @elseif($level == $currentLevel)
                        <div class="timeline-dot glow-dot" style="background:hsl(var(--color-primary));border:2px solid hsl(var(--color-surface));box-shadow:0 4px 6px hsl(var(--color-primary)/0.10);"></div>
                    @else
                        <div class="timeline-dot" style="background:hsl(var(--color-background));border:2px solid hsl(var(--color-border));"></div>
                    @endif
                    <div class="timeline-line h-12" style="background:hsl(var(--color-border));"></div>
                </div>
                <div class="flex-1 pb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold" style="color:{{ $level <= $currentLevel ? 'hsl(var(--color-text-primary))' : 'hsl(var(--color-text-muted))' }};">
                                المستوى {{ $level }}
                            </h3>
                            <p class="text-xs mt-0.5" style="color:hsl(var(--color-text-muted));">
                                @if($level < $currentLevel)
                                    مكتمل
                                @elseif($level == $currentLevel)
                                    قيد الدراسة
                                @else
                                    قادم
                                @endif
                                · {{ $creditsPerLevel }} ساعة معتمدة
                            </p>
                        </div>
                        @if($level < $currentLevel)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:hsl(var(--color-accent)/0.10);color:hsl(var(--color-accent));">مكتمل</span>
                        @elseif($level == $currentLevel)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:hsl(var(--color-primary)/0.10);color:hsl(var(--color-primary));">الحالي</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:hsl(var(--color-background));color:hsl(var(--color-text-muted));">قادم</span>
                        @endif
                    </div>
                    @if($level == $currentLevel)
                    <div class="mt-2 progress-track">
                        <div class="progress-fill" style="width:{{ min(100, ($graduationProgress ? $graduationProgress['completion_percentage'] - (($level-1) * 100/$totalLevels) * $totalLevels : 0)) }}%;background:hsl(var(--color-primary));"></div>
                    </div>
                    @endif
                </div>
            </div>
            @endfor

            {{-- Graduation Node --}}
            <div class="timeline-item flex items-start gap-4">
                <div class="flex flex-col items-center">
                    <div class="timeline-dot" style="background:hsl(var(--color-background));border:2px solid hsl(var(--color-border));">
                        <svg class="h-3 w-3 absolute inset-0 m-auto" style="color:hsl(var(--color-text-muted));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold" style="color:hsl(var(--color-primary));">التخرج</h3>
                            <p class="text-xs mt-0.5" style="color:hsl(var(--color-text-muted));">{{ $graduationProgress && $graduationProgress['estimated_graduation_date'] ? date('F Y', strtotime($graduationProgress['estimated_graduation_date'])) : 'غير محدد' }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:hsl(var(--color-primary)/0.10);color:hsl(var(--color-primary));">{{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }} ساعة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
