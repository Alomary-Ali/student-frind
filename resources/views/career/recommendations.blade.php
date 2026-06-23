@extends('layouts.dashboard')

@section('title', 'التوصيات المهنية')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">التوصيات المهنية</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">توصيات مخصصة لك</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-rf-card>
            <x-slot:title>المسارات الوظيفية</x-slot:title>
            @forelse($recommendations['career_paths'] as $rec)
            <div class="py-2 border-b last:border-0">
                <p class="font-medium">{{ $rec['path']->title }}</p>
                <p class="text-xs">التوافق: {{ $rec['match_score'] }}%</p>
            </div>
            @empty
            <x-rf-empty-state title="لا توجد توصيات" />
            @endforelse
        </x-rf-card>

        <x-rf-card>
            <x-slot:title>الفرص المتاحة</x-slot:title>
            @forelse($recommendations['opportunities'] as $opp)
            <div class="py-2 border-b last:border-0">
                <p class="font-medium text-sm">{{ $opp['reason'] ?? 'فرصة موصى بها' }}</p>
            </div>
            @empty
            <x-rf-empty-state title="لا توجد فرص" />
            @endforelse
        </x-rf-card>

        <x-rf-card>
            <x-slot:title>مسارات التعلم</x-slot:title>
            @forelse($recommendations['learning_paths'] as $lp)
            <div class="py-2 border-b last:border-0">
                <p class="font-medium text-sm">{{ $lp['title'] }}</p>
                <x-rf-progress :value="$lp['progress']" />
            </div>
            @empty
            <x-rf-empty-state title="لا توجد مسارات تعلم" />
            @endforelse
        </x-rf-card>
    </div>
</div>
@endsection
