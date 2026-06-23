@extends('layouts.dashboard')
@section('title', 'التذكيرات')
@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black text-text-primary">التذكيرات</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة تذكيراتك وإشعاراتك</p>
    </div>
    <a href="{{ route('productivity.reminders.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all shadow-md self-start sm:self-auto bg-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        تذكير جديد
    </a>
</div>

{{-- Reminders List --}}
@if(count($reminders) > 0)
    <div class="space-y-3">
        @foreach($reminders as $i => $reminder)
        <div class="unit-card p-5 animate-fade-in-up
            @if($reminder->isDue) border-accent-start @endif"
            style="animation-delay:{{ ($i % 8) * 50 }}ms;@if($reminder->isDue) border-color:hsl(var(--color-error)); @endif">

            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                    style="@if($reminder->isDue) background:hsl(var(--color-error)/0.10);color:hsl(var(--color-error));
                    @else background:hsl(var(--color-primary)/0.10);color:hsl(var(--color-primary)); @endif">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold" style="@if($reminder->isDue) color:hsl(var(--color-error)); @else color:hsl(var(--color-text-primary)); @endif">
                        {{ $reminder->message }}
                    </p>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-text-muted">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $reminder->triggerAt }}
                        </span>
                        <x-rf-badge variant="{{ $reminder->type === 'email' ? 'primary' : ($reminder->type === 'push' ? 'accent' : 'muted') }}">
                            @if($reminder->type === 'email') بريد إلكتروني
                            @elseif($reminder->type === 'push') إشعار فوري
                            @elseif($reminder->type === 'sms') رسالة نصية
                            @else {{ $reminder->type }} @endif
                        </x-rf-badge>
                        <x-rf-badge variant="{{ $reminder->status === 'triggered' ? 'accent' : ($reminder->status === 'dismissed' ? 'muted' : 'primary') }}">
                            @if($reminder->status === 'triggered') تم التشغيل
                            @elseif($reminder->status === 'dismissed') مُجاهَل
                            @else منتظر @endif
                        </x-rf-badge>
                        @if($reminder->isDue)
                            <x-rf-badge variant="error">موعد الآن</x-rf-badge>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="p-16 rounded-xl border border-border bg-surface shadow-sm text-center animate-scale-in">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-primary/8">
            <svg class="h-10 w-10 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد تذكيرات بعد</p>
        <p class="text-sm mb-6 text-text-muted">أضف تذكيراً لتبقى على المسار الصحيح</p>
        <a href="{{ route('productivity.reminders.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all shadow-lg bg-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            إنشاء تذكير جديد
        </a>
    </div>
@endif

@endsection
