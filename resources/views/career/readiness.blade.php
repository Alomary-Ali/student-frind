@extends('layouts.dashboard')

@section('title', 'الجاهزية المهنية')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">الجاهزية المهنية</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">مدى جاهزيتك لسوق العمل</h1>
        </div>
    </div>

    <x-rf-kpi-card title="الدرجة الإجمالية" :value="$score . '%'" variant="primary" class="w-full" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($breakdown as $key => $item)
        <x-rf-card>
            <x-slot:title>{{ __("career.breakdown.$key") }}</x-slot:title>
            <div class="space-y-2">
                <x-rf-progress :value="$item['score']" />
                <p class="text-xs">الوزن: {{ $item['weight'] * 100 }}% • المساهمة: {{ $item['contribution'] }}%</p>
            </div>
        </x-rf-card>
        @endforeach
    </div>
</div>
@endsection
