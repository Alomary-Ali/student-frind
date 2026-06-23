@extends('layouts.dashboard')
@section('title', 'المشاريع')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black" style="color:hsl(var(--color-text-primary));">المشاريع</h1>
        <p class="text-sm mt-0.5" style="color:hsl(var(--color-text-muted));">إدارة ومتابعة مشاريعك الأكاديمية والشخصية</p>
    </div>
</div>

{{-- Projects List --}}
@if(isset($projects) && count($projects) > 0)
    <div class="space-y-3">
        @foreach($projects as $i => $project)
        <div class="unit-card p-5 animate-fade-in-up" style="animation-delay:{{ ($i % 8) * 50 }}ms">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="text-sm font-black" style="color:hsl(var(--color-text-primary));">{{ $project->title() }}</h3>
                        <x-rf-badge variant="{{ $project->status()->value === 'completed' ? 'accent' : ($project->status()->value === 'in_progress' ? 'primary' : ($project->status()->value === 'cancelled' ? 'error' : 'navy')) }}">
                            @if($project->status()->value === 'completed') مكتمل
                            @elseif($project->status()->value === 'in_progress') قيد التنفيذ
                            @elseif($project->status()->value === 'cancelled') ملغي
                            @else {{ $project->status()->value }} @endif
                        </x-rf-badge>
                    </div>
                    @if($project->description())
                    <p class="text-[12.5px] mb-3" style="color:hsl(var(--color-text-muted));">{{ $project->description() }}</p>
                    @endif
                    <div class="mb-3">
                        <div class="flex justify-between text-[11px] font-bold mb-1.5">
                            <span style="color:hsl(var(--color-text-muted));">التقدم</span>
                            <span style="color:hsl(var(--color-navy));">{{ $project->progressPercentage() }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill progress-fill-primary" style="width: {{ $project->progressPercentage() }}%"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] font-medium" style="color:hsl(var(--color-text-muted));">
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            البداية: {{ $project->startDate()->format('Y-m-d') }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            النهاية: {{ $project->dueDate()->format('Y-m-d') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('productivity.projects.show', $project->id()->value()) }}"
                   class="shrink-0 inline-flex items-center gap-1.5 text-[12px] font-bold hover:text-accent transition-colors mt-1" style="color:hsl(var(--color-navy));">
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
    <div class="unit-card p-16 text-center animate-scale-in">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background:hsl(var(--color-navy)/0.10);">
            <svg class="h-10 w-10" style="color:hsl(var(--color-navy)/0.30);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2" style="color:hsl(var(--color-text-primary));">لا توجد مشاريع بعد</p>
        <p class="text-sm mb-6" style="color:hsl(var(--color-text-muted));">ابدأ بإنشاء مشروع جديد لتتبع تقدمك</p>
    </div>
@endif

@endsection
