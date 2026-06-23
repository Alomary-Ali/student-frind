@extends('layouts.dashboard')
@section('title', 'الواجبات')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">الواجبات</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة ومتابعة واجباتك الأكاديمية</p>
    </div>
</div>

{{-- Assignments List --}}
@if(isset($assignments) && count($assignments) > 0)
    <div class="space-y-3">
        @foreach($assignments as $i => $assignment)
        <div class="unit-card p-5 animate-fade-in-up" style="animation-delay:{{ ($i % 8) * 50 }}ms">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="text-sm font-black text-text-primary">{{ $assignment->title() }}</h3>
                        <x-rf-badge variant="{{ $assignment->status()->value === 'graded' ? 'accent' : ($assignment->status()->value === 'submitted' ? 'primary' : ($assignment->status()->value === 'late' ? 'error' : 'muted')) }}">
                            @if($assignment->status()->value === 'graded') تم التقييم
                            @elseif($assignment->status()->value === 'submitted') تم التسليم
                            @elseif($assignment->status()->value === 'late') متأخر
                            @else معلق @endif
                        </x-rf-badge>
                    </div>
                    <p class="text-[12.5px] mb-3 text-text-muted">{{ $assignment->courseId() }}</p>
                    <div class="flex items-center gap-3 text-[11px] font-medium text-text-muted">
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($assignment->dueDate()->format('Y-m-d H:i:s'))->format('Y-m-d') }}
                        </span>
                        @if($assignment->grade())
                        <span class="inline-flex items-center gap-1 text-accent">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            الدرجة: {{ $assignment->grade() }}
                        </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('productivity.assignments.show', $assignment->id) }}"
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
        <p class="text-base font-black mb-2 text-text-primary">لا توجد واجبات بعد</p>
        <p class="text-sm mb-6 text-text-muted">ابدأ بإضافة واجب جديد لتتبع تقدمك</p>
    </div>
@endif

@endsection
