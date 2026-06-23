@extends('layouts.dashboard')
@section('title', $goal ? $goal->title() : 'الهدف')
@section('content')

@if($goal)
{{-- Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('productivity.goals') }}"
       class="inline-flex items-center gap-1.5 text-[12px] font-bold hover:text-navy transition-colors text-text-muted">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        العودة للأهداف
    </a>
</div>

<div class="p-6 rounded-xl border border-border bg-surface shadow-sm animate-fade-in-up">
    {{-- Title + Badges --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <h1 class="text-xl font-black">{{ $goal->title() }}</h1>
        <x-rf-badge variant="{{ $p === 'urgent' ? 'error' : ($p === 'high' ? 'primary' : 'muted') }}">
            @if($p === 'urgent') عاجل
            @elseif($p === 'high') مرتفع
            @elseif($p === 'medium') متوسط
            @else عادي @endif
        </x-rf-badge>
        <x-rf-badge variant="{{ $s === 'completed' ? 'accent' : ($s === 'active' ? 'primary' : 'muted') }}">
            @if($s === 'completed') مكتمل
            @elseif($s === 'active') نشط
            @else معلق @endif
        </x-rf-badge>
        <x-rf-badge variant="muted">
            @if($goal->goalType()->value === 'academic') أكاديمي
            @elseif($goal->goalType()->value === 'personal') شخصي
            @elseif($goal->goalType()->value === 'career') مهني
            @else {{ $goal->goalType()->value }} @endif
        </x-rf-badge>
        @if($goal->isOverdue())
        <x-rf-badge variant="error">متأخر</x-rf-badge>
        @endif
    </div>

    {{-- Description --}}
    <div class="mb-6">
        <p class="text-[13px] leading-relaxed text-text-muted">{{ $goal->description() }}</p>
    </div>

    {{-- Progress --}}
    <div class="mb-6">
        <div class="flex justify-between text-sm font-bold mb-2">
            <span class="text-text-muted">التقدم</span>
            <span class="text-primary">{{ number_format($goal->progress()->value(), 1) }}%</span>
        </div>
        <div class="progress-track h-3">
            <div class="progress-fill
                @if($goal->status()->value === 'completed') progress-fill-accent
                @else progress-fill-primary @endif"
                style="width: {{ $goal->progress()->value() }}%"></div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">الموعد النهائي</p>
            <p class="text-[13px] font-bold text-text-primary">{{ $goal->targetDate()->format('Y-m-d') }}</p>
        </div>
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">تاريخ الإنشاء</p>
            <p class="text-[13px] font-bold text-text-primary">{{ $goal->createdAt()->format('Y-m-d') }}</p>
        </div>
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">الأولوية</p>
            <p class="text-[13px] font-bold text-text-primary">
                @if($goal->priority()->value() === 'urgent') عاجل
                @elseif($goal->priority()->value() === 'high') مرتفع
                @elseif($goal->priority()->value() === 'medium') متوسط
                @else عادي @endif
            </p>
        </div>
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">الحالة</p>
            <p class="text-[13px] font-bold text-text-primary">
                @if($goal->status()->value === 'completed') مكتمل
                @elseif($goal->status()->value === 'active') نشط
                @else معلق @endif
            </p>
        </div>
    </div>
</div>
@else
<div class="p-16 rounded-xl border border-border bg-surface shadow-sm text-center animate-scale-in">
    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-error/10">
        <svg class="h-10 w-10" style="color:hsl(var(--color-error)/0.50);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <p class="text-base font-black mb-2 text-text-primary">الهدف غير موجود</p>
    <p class="text-sm mb-6 text-text-muted">لم يتم العثور على الهدف المطلوب</p>
    <a href="{{ route('productivity.goals') }}"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all bg-primary">
        العودة للأهداف
    </a>
</div>
@endif

@endsection
