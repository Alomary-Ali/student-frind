@extends('layouts.dashboard')

@section('title', 'المسارات الوظيفية')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">المسارات الوظيفية</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">استكشف المسارات الوظيفية</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">اكتشف المهارات المطلوبة والمراحل لكل مسار مهني.</p>
        </div>
    </div>

    <form method="GET" class="flex gap-3">
        <input type="text" name="target_role" value="{{ request('target_role') }}" placeholder="ابحث عن مسار..." class="flex-1 rounded-xl border px-4 py-2 text-sm" />
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($paths as $path)
        <a href="{{ route('career.paths.show', $path->id) }}" class="block no-underline">
            <x-rf-card>
                <x-slot:title>{{ $path->title }}</x-slot:title>
                <p class="text-sm">{{ $path->targetRole }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach(array_slice($path->requiredSkills, 0, 4) as $skill)
                    <x-rf-badge variant="secondary" class="text-xs">{{ $skill }}</x-rf-badge>
                    @endforeach
                    @if(count($path->requiredSkills) > 4)
                    <x-rf-badge variant="ghost" class="text-xs">+{{ count($path->requiredSkills) - 4 }}</x-rf-badge>
                    @endif
                </div>
                <div class="flex justify-between mt-4 text-xs">
                    <span>{{ $path->totalDuration }} شهر</span>
                    @if($path->averageSalary)
                    <span>{{ $path->averageSalary }}</span>
                    @endif
                </div>
            </x-rf-card>
        </a>
        @empty
        <div class="col-span-full">
            <x-rf-empty-state title="لا توجد مسارات متاحة" />
        </div>
        @endforelse
    </div>
</div>
@endsection
