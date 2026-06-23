@extends('layouts.dashboard')
@section('title', 'تفاصيل الاختبار')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">{{ $exam->title() }}</h1>
        <p class="text-sm mt-0.5 text-text-muted">{{ $exam->courseId() }}</p>
    </div>
</div>

@if(isset($exam))
<div class="space-y-6">
    {{-- Exam Details --}}
    <div class="unit-card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">الحالة</p>
                <x-rf-badge variant="{{ $exam->status() === 'completed' ? 'accent' : ($exam->status() === 'scheduled' ? 'primary' : ($exam->status() === 'cancelled' ? 'error' : 'navy')) }}">
                    @if($exam->status() === 'completed') مكتمل
                    @elseif($exam->status() === 'scheduled') مجدول
                    @elseif($exam->status() === 'cancelled') ملغي
                    @else {{ $exam->status() }} @endif
                </x-rf-badge>
            </div>
            @if(method_exists($exam, 'readinessStatus') && $exam->readinessStatus())
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">الجاهزية</p>
                <x-rf-badge variant="{{ $exam->readinessStatus()->value === 'fully_ready' ? 'accent' : ($exam->readinessStatus()->value === 'partially_ready' ? 'primary' : ($exam->readinessStatus()->value === 'needs_review' ? 'warning' : 'navy')) }}">
                    @if($exam->readinessStatus()->value === 'fully_ready') جاهز تماماً
                    @elseif($exam->readinessStatus()->value === 'partially_ready') جاهز جزئياً
                    @elseif($exam->readinessStatus()->value === 'needs_review') بحاجة لمراجعة
                    @else غير مستعد @endif
                </x-rf-badge>
            </div>
            @endif
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">التاريخ</p>
                <p class="text-sm font-bold text-text-primary">{{ \Carbon\Carbon::parse($exam->examDate()->format('Y-m-d H:i:s'))->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">المكان</p>
                <p class="text-sm font-bold text-text-primary">{{ $exam->location() }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">النوع</p>
                <p class="text-sm font-bold text-text-primary">
                    @if($exam->examType()->value === 'midterm') نصفي
                    @elseif($exam->examType()->value === 'final') نهائي
                    @elseif($exam->examType()->value === 'quiz') اختبار قصير
                    @else {{ $exam->examType()->value }} @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('productivity.exams.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all bg-border text-text-muted">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة للقائمة
        </a>
    </div>
</div>
@else
<div class="unit-card p-16 text-center animate-scale-in">
    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-navy/10">
        <svg class="h-10 w-10 text-navy/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </div>
    <p class="text-base font-black mb-2 text-text-primary">الاختبار غير موجود</p>
    <a href="{{ route('productivity.exams.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all bg-navy">
        العودة للقائمة
    </a>
</div>
@endif

@endsection
