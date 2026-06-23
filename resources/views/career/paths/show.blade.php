@extends('layouts.dashboard')

@section('title', $path->title)

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">{{ $path->targetRole }}</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">{{ $path->title }}</h1>
            @if($path->description)
            <p class="text-sm mt-2" style="color: hsl(var(--color-surface) / 0.7);">{{ $path->description }}</p>
            @endif
            <div class="flex gap-4 mt-4 text-sm">
                @if($path->averageSalary)
                <x-rf-badge>الراتب: {{ $path->averageSalary }}</x-rf-badge>
                @endif
                <x-rf-badge>المدة: {{ $path->totalDuration }} شهر</x-rf-badge>
                @if($path->growthRate)
                <x-rf-badge>النمو: {{ $path->growthRate }}</x-rf-badge>
                @endif
            </div>
        </div>
    </div>

    {{-- Required Skills --}}
    @if(!empty($path->requiredSkills))
    <x-rf-card>
        <x-slot:title>المهارات المطلوبة</x-slot:title>
        <div class="flex flex-wrap gap-2">
            @foreach($path->requiredSkills as $skill)
            <x-rf-badge variant="secondary">{{ $skill }}</x-rf-badge>
            @endforeach
        </div>
    </x-rf-card>
    @endif

    {{-- Stages Timeline --}}
    <x-rf-card>
        <x-slot:title>مراحل المسار</x-slot:title>
        <div class="space-y-4">
            @foreach($path->stages as $index => $stage)
            <div class="relative pr-8 pb-4 border-r-2 border-primary-200 last:border-0 last:pb-0">
                <div class="absolute right-[-9px] top-0 w-4 h-4 rounded-full bg-primary-500"></div>
                <h3 class="font-medium">{{ $stage->title }}</h3>
                <p class="text-xs mt-1">الترتيب: {{ $stage->order }} • المدة: {{ $stage->durationMonths }} شهر</p>
                @if($stage->salaryRange)
                <p class="text-xs">الراتب: {{ $stage->salaryRange }}</p>
                @endif
                @if($stage->description)
                <p class="text-sm mt-1">{{ $stage->description }}</p>
                @endif
                @if(!empty($stage->requiredSkills))
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($stage->requiredSkills as $skill)
                    <x-rf-badge variant="ghost" class="text-xs">{{ $skill }}</x-rf-badge>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </x-rf-card>
</div>
@endsection
