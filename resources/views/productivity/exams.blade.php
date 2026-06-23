@extends('layouts.dashboard')
@section('title', 'الاختبارات')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">الاختبارات</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة ومتابعة اختباراتك الأكاديمية</p>
    </div>
</div>

{{-- Exams List --}}
@if(isset($exams) && count($exams) > 0)
    <div class="space-y-3">
        @foreach($exams as $i => $exam)
        <div class="unit-card p-5 animate-fade-in-up" style="animation-delay:{{ ($i % 8) * 50 }}ms">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="text-sm font-black text-text-primary">{{ $exam->title() }}</h3>
                        <x-rf-badge variant="{{ $exam->status() === 'completed' ? 'accent' : ($exam->status() === 'scheduled' ? 'primary' : ($exam->status() === 'cancelled' ? 'error' : 'muted')) }}">
                            @if($exam->status() === 'completed') مكتمل
                            @elseif($exam->status() === 'scheduled') مجدول
                            @elseif($exam->status() === 'cancelled') ملغي
                            @else {{ $exam->status() }} @endif
                        </x-rf-badge>
                        @if(method_exists($exam, 'readinessStatus') && $exam->readinessStatus())
                            <x-rf-badge variant="{{ $exam->readinessStatus()->value === 'fully_ready' ? 'accent' : ($exam->readinessStatus()->value === 'partially_ready' ? 'primary' : ($exam->readinessStatus()->value === 'needs_review' ? 'warning' : 'muted')) }}">
                                @if($exam->readinessStatus()->value === 'fully_ready') جاهز تماماً
                                @elseif($exam->readinessStatus()->value === 'partially_ready') جاهز جزئياً
                                @elseif($exam->readinessStatus()->value === 'needs_review') بحاجة لمراجعة
                                @else غير مستعد @endif
                            </x-rf-badge>
                        @endif
                    </div>
                    <p class="text-[12.5px] mb-3 text-text-muted">{{ $exam->courseId() }}</p>
                    <div class="flex items-center gap-3 text-[11px] font-medium text-text-muted">
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($exam->examDate()->format('Y-m-d H:i:s'))->format('Y-m-d') }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $exam->location() }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('productivity.exams.show', $exam->id) }}"
                   class="shrink-0 inline-flex items-center gap-1.5 text-[12px] font-bold hover:text-accent transition-colors mt-1 text-primary">
                    التفاصيل
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="p-16 rounded-xl border border-border bg-surface shadow-sm text-center animate-scale-in">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-primary/8">
            <svg class="h-10 w-10 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد اختبارات بعد</p>
        <p class="text-sm mb-6 text-text-muted">ابدأ بإضافة اختبار جديد لتتبع تقدمك</p>
    </div>
@endif

@endsection
