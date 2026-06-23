@extends('layouts.dashboard')
@section('title', 'المهام')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">المهام</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة مهامك اليومية والأكاديمية</p>
    </div>
    <a href="{{ route('productivity.tasks.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all shadow-md self-start sm:self-auto bg-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        مهمة جديدة
    </a>
</div>

{{-- Tasks List --}}
@if(count($tasks) > 0)
    <div class="space-y-3">
        @foreach($tasks as $i => $task)
        <div class="unit-card p-5 animate-fade-in-up
            @if($task->status === 'completed') opacity-75 @endif"
            style="animation-delay:{{ ($i % 8) * 50 }}ms">

            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    {{-- Status icon --}}
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5
                        @if($task->status === 'completed') bg-accent/15 text-accent
                        @elseif($task->priority === 'urgent') bg-error/10 text-error
                        @elseif($task->priority === 'high') bg-primary/10 text-primary
                        @else bg-border text-text-muted @endif">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            @if($task->status === 'completed')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            @elseif($task->status === 'in_progress')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            @endif
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        {{-- Title + badges --}}
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-sm font-black @if($task->status === 'completed') line-through text-text-muted @else text-text-primary @endif">
                                {{ $task->title }}
                            </h3>
                            <x-rf-badge variant="{{ $task->priority === 'urgent' ? 'error' : ($task->priority === 'high' ? 'primary' : 'muted') }}">
                                @if($task->priority === 'urgent') عاجل
                                @elseif($task->priority === 'high') مرتفع
                                @elseif($task->priority === 'medium') متوسط
                                @else عادي @endif
                            </x-rf-badge>
                            <x-rf-badge variant="{{ $task->status === 'completed' ? 'accent' : ($task->status === 'in_progress' ? 'primary' : ($task->status === 'postponed' ? 'warning' : 'muted')) }}">
                                @if($task->status === 'completed') مكتمل
                                @elseif($task->status === 'in_progress') قيد التنفيذ
                                @elseif($task->status === 'postponed') مؤجل
                                @else معلق @endif
                            </x-rf-badge>
                        </div>

                        {{-- Description --}}
                        @if($task->description)
                        <p class="text-[12.5px] leading-relaxed mb-2 text-text-muted">{{ $task->description }}</p>
                        @endif

                        {{-- Meta --}}
                        <div class="flex items-center gap-3 flex-wrap">
                            @if($task->dueDate)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-text-muted">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $task->dueDate }}
                            </span>
                            @endif
                            @if($task->linkedGoalId)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-text-muted">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                مرتبط بهدف
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0">
                    @if($task->status !== 'completed')
                    <form method="POST" action="{{ route('productivity.tasks.complete', $task->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 text-[12px] font-bold transition-colors text-accent">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            إكمال
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('productivity.tasks.show', $task->id) }}"
                       class="inline-flex items-center gap-1 text-[12px] font-bold hover:text-accent transition-colors text-primary">
                        التفاصيل
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="p-16 rounded-xl border border-border bg-surface shadow-sm text-center animate-scale-in">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-primary/8">
            <svg class="h-10 w-10 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد مهام بعد</p>
        <p class="text-sm mb-6 text-text-muted">أنشئ مهمتك الأولى وابدأ يومك بنشاط</p>
        <a href="{{ route('productivity.tasks.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all shadow-lg bg-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            إنشاء مهمة جديدة
        </a>
    </div>
@endif

@endsection
