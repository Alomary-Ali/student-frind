@extends('layouts.dashboard')
@section('title', 'الأهداف')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black">الأهداف</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة أهدافك الشخصية والأكاديمية</p>
    </div>
    <a href="{{ route('productivity.goals.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all shadow-md self-start sm:self-auto bg-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        إنشاء هدف جديد
    </a>
</div>

{{-- Goals List --}}
@if(count($goals) > 0)
    <div class="space-y-3">
        @foreach($goals as $i => $goal)
        <div class="unit-card p-5 animate-fade-in-up" style="animation-delay:{{ ($i % 8) * 50 }}ms">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    {{-- Title + badges --}}
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="text-sm font-black">{{ $goal->title }}</h3>
                        <x-rf-badge variant="{{ $goal->priority === 'urgent' ? 'error' : ($goal->priority === 'high' ? 'primary' : 'muted') }}">
                            @if($goal->priority === 'urgent') عاجل
                            @elseif($goal->priority === 'high') مرتفع
                            @elseif($goal->priority === 'medium') متوسط
                            @else عادي @endif
                        </x-rf-badge>
                        <x-rf-badge variant="{{ $goal->status === 'completed' ? 'accent' : ($goal->status === 'active' ? 'primary' : 'muted') }}">
                            @if($goal->status === 'completed') مكتمل
                            @elseif($goal->status === 'active') نشط
                            @else معلق @endif
                        </x-rf-badge>
                        <x-rf-badge variant="muted">
                            @if($goal->goalType === 'academic') أكاديمي
                            @elseif($goal->goalType === 'personal') شخصي
                            @elseif($goal->goalType === 'career') مهني
                            @else {{ $goal->goalType }} @endif
                        </x-rf-badge>
                    </div>

                    {{-- Description --}}
                    <p class="text-[12.5px] mb-4 leading-relaxed text-text-muted">{{ $goal->description }}</p>

                    {{-- Progress --}}
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex justify-between text-[11px] font-bold mb-1.5">
                                <span class="text-text-muted">التقدم</span>
                                <span class="text-primary">{{ number_format($goal->progress, 1) }}%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill
                                    @if($goal->status === 'completed') progress-fill-accent
                                    @else progress-fill-primary @endif"
                                    style="width: {{ $goal->progress }}%"></div>
                            </div>
                        </div>
                        @if($goal->targetDate)
                        <div class="text-right shrink-0">
                            <p class="text-[10px] font-medium text-text-muted">الموعد النهائي</p>
                            <p class="text-[11px] font-bold text-text-primary">{{ $goal->targetDate }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Action --}}
                <a href="{{ route('productivity.goals.show', $goal->id) }}"
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد أهداف بعد</p>
        <p class="text-sm mb-6 text-text-muted">حدد هدفك الأول وابدأ رحلة النجاح</p>
        <a href="{{ route('productivity.goals.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all shadow-lg bg-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            إنشاء هدف جديد
        </a>
    </div>
@endif

@endsection
