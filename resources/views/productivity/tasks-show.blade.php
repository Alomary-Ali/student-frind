@extends('layouts.dashboard')
@section('title', $task ? $task->title() : 'المهمة')
@section('content')

@if($task)
{{-- Header --}}
<div class="flex items-center justify-between gap-3 mb-6">
    <a href="{{ route('productivity.tasks') }}"
       class="inline-flex items-center gap-1.5 text-[12px] font-bold hover:text-navy transition-colors text-text-muted">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        العودة للمهام
    </a>

    @if($task->status()->value !== 'completed')
    <form method="POST" action="{{ route('productivity.tasks.complete', $task->id()->value()) }}">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all shadow-md bg-accent">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            إكمال المهمة
        </button>
    </form>
    @endif
</div>

<div class="unit-card p-6 animate-fade-in-up">
    {{-- Title + Badges --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center
            @if($task->status()->value === 'completed') bg-accent/15 text-accent
            @elseif($task->priority()->value() === 'urgent') bg-error/10 text-error
            @elseif($task->priority()->value() === 'high') bg-navy/10 text-navy
            @else bg-border text-text-muted @endif">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                @if($task->status()->value === 'completed')
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                @elseif($task->status()->value === 'in_progress')
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                @endif
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-black @if($task->status()->value === 'completed') line-through text-text-muted @else text-text-primary @endif">
                {{ $task->title() }}
            </h1>
            <div class="flex flex-wrap items-center gap-2 mt-1">
                <x-rf-badge variant="{{ $p === 'urgent' ? 'error' : ($p === 'high' ? 'primary' : 'navy') }}">
                    @if($p === 'urgent') عاجل
                    @elseif($p === 'high') مرتفع
                    @elseif($p === 'medium') متوسط
                    @else عادي @endif
                </x-rf-badge>
                <x-rf-badge variant="{{ $s === 'completed' ? 'accent' : ($s === 'in_progress' ? 'primary' : ($s === 'postponed' ? 'warning' : 'navy')) }}">
                    @if($s === 'completed') مكتمل
                    @elseif($s === 'in_progress') قيد التنفيذ
                    @elseif($s === 'postponed') مؤجل
                    @else معلق @endif
                </x-rf-badge>
                @if($task->isOverdue())
                <x-rf-badge variant="error">متأخر</x-rf-badge>
                @endif
            </div>
        </div>
    </div>

    {{-- Description --}}
    @if($task->description())
    <div class="mb-6">
        <p class="text-[13px] leading-relaxed text-text-muted">{{ $task->description() }}</p>
    </div>
    @endif

    {{-- Details Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">تاريخ الاستحقاق</p>
            <p class="text-[13px] font-bold text-text-primary">
                {{ $task->dueDate() ? $task->dueDate()->format('Y-m-d') : '—' }}
            </p>
        </div>
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">تاريخ الإنشاء</p>
            <p class="text-[13px] font-bold text-text-primary">{{ $task->createdAt()->format('Y-m-d') }}</p>
        </div>
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">الأولوية</p>
            <p class="text-[13px] font-bold text-text-primary">
                @if($task->priority()->value() === 'urgent') عاجل
                @elseif($task->priority()->value() === 'high') مرتفع
                @elseif($task->priority()->value() === 'medium') متوسط
                @else عادي @endif
            </p>
        </div>
        <div class="rounded-xl p-4 bg-background">
            <p class="text-[10px] font-medium mb-1 text-text-muted">الحالة</p>
            <p class="text-[13px] font-bold text-text-primary">
                @if($task->status()->value === 'completed') مكتمل
                @elseif($task->status()->value === 'in_progress') قيد التنفيذ
                @elseif($task->status()->value === 'postponed') مؤجل
                @else معلق @endif
            </p>
        </div>
    </div>

    {{-- Linked Goal --}}
    @if($task->linkedGoalId())
    <div class="mt-4 rounded-xl p-4 flex items-center gap-3 bg-navy/10">
        <svg class="h-5 w-5 shrink-0 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
        </svg>
        <div>
            <p class="text-[11px] font-medium text-text-muted">مرتبط بهدف</p>
            <p class="text-[13px] font-bold text-navy">{{ $task->linkedGoalId() }}</p>
        </div>
    </div>
    @endif

    {{-- Completion Info --}}
    @if($task->completedAt())
    <div class="mt-4 rounded-xl p-4 flex items-center gap-3" style="background:hsl(var(--color-accent)/0.15);">
        <svg class="h-5 w-5 shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-[11px] font-medium text-text-muted">تم الإكمال</p>
            <p class="text-[13px] font-bold text-accent">{{ $task->completedAt()->format('Y-m-d H:i') }}</p>
        </div>
    </div>
    @endif
</div>
@else
<div class="unit-card p-16 text-center animate-scale-in">
    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-error/10">
        <svg class="h-10 w-10" style="color:hsl(var(--color-error)/0.50);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <p class="text-base font-black mb-2 text-text-primary">المهمة غير موجودة</p>
    <p class="text-sm mb-6 text-text-muted">لم يتم العثور على المهمة المطلوبة</p>
    <a href="{{ route('productivity.tasks') }}"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all bg-navy">
        العودة للمهام
    </a>
</div>
@endif

@endsection
