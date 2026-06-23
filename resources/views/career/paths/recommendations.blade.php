@extends('layouts.dashboard')

@section('title', 'المسارات المقترحة')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">بناءً على مهاراتك</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">المسارات الوظيفية المقترحة</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">مسارات وظيفية مقترحة بناءً على ملفك المهني ومهاراتك الحالية.</p>
        </div>
    </div>

    @forelse($recommendations as $rec)
    <x-rf-card>
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-medium">{{ $rec['path']->title }}</h3>
                <p class="text-xs">{{ $rec['path']->targetRole }}</p>
            </div>
            <x-rf-badge variant="{{ $rec['match_score'] >= 70 ? 'success' : ($rec['match_score'] >= 40 ? 'warning' : 'secondary') }}">
                {{ $rec['match_score'] }}% توافق
            </x-rf-badge>
        </div>
        <x-rf-progress :value="$rec['match_score']" class="mt-2" />
        @if(!empty($rec['matched_skills']))
        <div class="mt-3">
            <p class="text-xs font-medium mb-1">المهارات المتوفرة:</p>
            <div class="flex flex-wrap gap-1">
                @foreach($rec['matched_skills'] as $skill)
                <x-rf-badge variant="success" class="text-xs">{{ $skill }}</x-rf-badge>
                @endforeach
            </div>
        </div>
        @endif
        @if(!empty($rec['missing_skills']))
        <div class="mt-2">
            <p class="text-xs font-medium mb-1">المهارات المطلوبة:</p>
            <div class="flex flex-wrap gap-1">
                @foreach($rec['missing_skills'] as $skill)
                <x-rf-badge variant="secondary" class="text-xs">{{ $skill }}</x-rf-badge>
                @endforeach
            </div>
        </div>
        @endif
    </x-rf-card>
    @empty
    <x-rf-empty-state title="لم يتم العثور على توصيات" description="أضف مهاراتك أولاً للحصول على توصيات مخصصة" />
    @endforelse
</div>
@endsection
