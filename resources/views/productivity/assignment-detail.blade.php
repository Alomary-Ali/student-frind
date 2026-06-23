@extends('layouts.dashboard')
@section('title', 'تفاصيل الواجب')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">{{ $assignment->title() }}</h1>
        <p class="text-sm mt-0.5 text-text-muted">{{ $assignment->courseId() }}</p>
    </div>
</div>

@if(isset($assignment))
<div class="space-y-6">
    {{-- Assignment Details --}}
    <div class="unit-card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">الحالة</p>
                <x-rf-badge variant="{{ $assignment->status()->value === 'graded' ? 'accent' : ($assignment->status()->value === 'submitted' ? 'primary' : ($assignment->status()->value === 'late' ? 'error' : 'navy')) }}">
                    @if($assignment->status()->value === 'graded') تم التقييم
                    @elseif($assignment->status()->value === 'submitted') تم التسليم
                    @elseif($assignment->status()->value === 'late') متأخر
                    @else معلق @endif
                </x-rf-badge>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">الموعد النهائي</p>
                <p class="text-sm font-bold text-text-primary">{{ \Carbon\Carbon::parse($assignment->dueDate()->format('Y-m-d H:i:s'))->format('Y-m-d H:i') }}</p>
            </div>
            @if($assignment->grade())
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">الدرجة</p>
                <p class="text-lg font-black text-accent">{{ $assignment->grade() }}</p>
            </div>
            @endif
            @if($assignment->submissionUrl())
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5 text-text-muted">رابط التسليم</p>
                <a href="{{ $assignment->submissionUrl() }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-bold hover:text-accent transition-colors text-navy">
                    عرض التسليم
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Description --}}
    @if($assignment->description())
    <div class="unit-card p-6">
        <h2 class="text-sm font-black mb-3 text-text-primary">الوصف</h2>
        <p class="text-sm leading-relaxed text-text-muted">{{ $assignment->description() }}</p>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('productivity.assignments.index') }}"
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
    <p class="text-base font-black mb-2 text-text-primary">الواجب غير موجود</p>
    <a href="{{ route('productivity.assignments.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all bg-navy">
        العودة للقائمة
    </a>
</div>
@endif

@endsection
