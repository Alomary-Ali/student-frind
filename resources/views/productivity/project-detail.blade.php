@extends('layouts.dashboard')
@section('title', 'تفاصيل المشروع')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black" style="color:hsl(var(--color-text-primary));">{{ $project->title() }}</h1>
        <p class="text-sm mt-0.5" style="color:hsl(var(--color-text-muted));">{{ $project->description() }}</p>
    </div>
</div>

<div class="space-y-6">
    {{-- Project Details --}}
    <div class="unit-card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:hsl(var(--color-text-muted));">الحالة</p>
                <x-rf-badge variant="{{ $project->status()->value === 'completed' ? 'accent' : ($project->status()->value === 'in_progress' ? 'primary' : ($project->status()->value === 'cancelled' ? 'error' : 'navy')) }}">
                    @if($project->status()->value === 'completed') مكتمل
                    @elseif($project->status()->value === 'in_progress') قيد التنفيذ
                    @elseif($project->status()->value === 'cancelled') ملغي
                    @else {{ $project->status()->value }} @endif
                </x-rf-badge>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:hsl(var(--color-text-muted));">التقدم</p>
                <div class="flex items-center gap-3">
                    <div class="flex-1 progress-track">
                        <div class="progress-fill progress-fill-primary" style="width: {{ $project->progressPercentage() }}%"></div>
                    </div>
                    <span class="text-sm font-bold" style="color:hsl(var(--color-navy));">{{ $project->progressPercentage() }}%</span>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:hsl(var(--color-text-muted));">تاريخ البدء</p>
                <p class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">{{ $project->startDate()->format('Y-m-d') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:hsl(var(--color-text-muted));">تاريخ الانتهاء</p>
                <p class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">{{ $project->dueDate()->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('productivity.projects.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all" style="background:hsl(var(--color-border));color:hsl(var(--color-text-muted));">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة للقائمة
        </a>
    </div>
</div>

@endsection
