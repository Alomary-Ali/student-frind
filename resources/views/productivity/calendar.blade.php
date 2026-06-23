@extends('layouts.dashboard')
@section('title', 'التقويم')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">التقويم</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة جدولك ومواعيدك</p>
    </div>
    <a href="{{ route('productivity.calendar.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all shadow-md self-start sm:self-auto bg-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        حدث جديد
    </a>
</div>

{{-- Events List --}}
@if(count($events) > 0)
    <div class="space-y-3">
        @foreach($events as $i => $event)
        <div class="unit-card p-5 animate-fade-in-up"
             style="animation-delay:{{ ($i % 8) * 50 }}ms">

            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                        style="
                        @if($event->isOngoing) background:hsl(var(--color-accent)/0.15);color:hsl(var(--color-accent));
                        @elseif($event->isPast) background:hsl(var(--color-border));color:hsl(var(--color-text-muted));
                        @else background:hsl(var(--color-primary)/0.10);color:hsl(var(--color-primary)); @endif
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-black" style="color:{{ $event->isPast ? 'hsl(var(--color-text-muted))' : 'hsl(var(--color-text-primary))' }};">
                            {{ $event->title }}
                        </h3>
                        @if($event->description)
                            <p class="text-[12.5px] mt-1 leading-relaxed text-text-muted">{{ $event->description }}</p>
                        @endif
                        <div class="flex items-center gap-2 mt-2 text-[11px] font-medium text-text-muted">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $event->startsAt }}</span>
                            <span>—</span>
                            <span>{{ $event->endsAt }}</span>
                        </div>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="flex items-center gap-2 shrink-0">
                    <x-rf-badge variant="{{ $event->isAllDay ? 'primary' : 'muted' }}">
                        {{ $event->isAllDay ? 'كامل اليوم' : 'محدد الوقت' }}
                    </x-rf-badge>
                    @if($event->isPast)
                        <x-rf-badge variant="muted">منتهي</x-rf-badge>
                    @elseif($event->isOngoing)
                        <x-rf-badge variant="accent">جاري الآن</x-rf-badge>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="p-16 rounded-xl border border-border bg-surface shadow-sm text-center animate-scale-in">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-primary/8">
            <svg class="h-10 w-10 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد أحداث بعد</p>
        <p class="text-sm mb-6 text-text-muted">أضف أول حدث لتقويمك وابدأ بتنظيم وقتك</p>
        <a href="{{ route('productivity.calendar.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all shadow-lg bg-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            إضافة حدث جديد
        </a>
    </div>
@endif

@endsection
