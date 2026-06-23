@extends('layouts.dashboard')

@section('title', 'تفاصيل المقابلة')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">{{ __("career.interview_type.{$interview->type}") }}</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">نتيجة المقابلة</h1>
        </div>
    </div>

    @if($interview->score !== null)
    <x-rf-kpi-card title="الدرجة" :value="$interview->score . '%'" variant="primary" class="w-full" />
    @endif

    @if($interview->feedback)
    <x-rf-card>
        <x-slot:title>التقييم</x-slot:title>
        <p class="text-sm">{{ $interview->feedback }}</p>
    </x-rf-card>
    @endif

    @if(!empty($interview->questions))
    <x-rf-card>
        <x-slot:title>الأسئلة</x-slot:title>
        <div class="space-y-4">
            @foreach($interview->questions as $index => $q)
            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800">
                <p class="text-sm font-medium">{{ $index + 1 }}. {{ $q['question'] ?? '' }}</p>
                @if(!empty($q['category']))
                <x-rf-badge variant="secondary" class="mt-1">{{ $q['category'] }}</x-rf-badge>
                @endif
            </div>
            @endforeach
        </div>
    </x-rf-card>
    @endif
</div>
@endsection
