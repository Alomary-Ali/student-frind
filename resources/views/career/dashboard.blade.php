@extends('layouts.dashboard')

@section('title', 'لوحة المسار المهني')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">المسار المهني المتكامل</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">مرحباً بك في مسارك المهني</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">تابع تطورك المهني، استكشف الفرص، وابنِ مستقبلك خطوة بخطوة.</p>
        </div>
    </div>

    {{-- Readiness Score --}}
    <x-rf-kpi-card title="الجاهزية المهنية" :value="$dashboard->readinessScore . '%'" variant="primary" class="w-full">
        <div class="mt-4 space-y-2">
            @foreach($dashboard->readinessBreakdown as $key => $item)
            <div class="flex justify-between text-sm">
                <span>{{ __("career.breakdown.$key") }}</span>
                <span>{{ $item['score'] }}%</span>
            </div>
            @endforeach
        </div>
    </x-rf-kpi-card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-rf-card>
            <x-slot:title>الملف المهني</x-slot:title>
            @if($dashboard->profile)
            <p class="text-sm">{{ $dashboard->profile['major'] ?? '' }}</p>
            <p class="text-xs mt-2">{{ count($dashboard->profile['portfolio_items'] ?? []) }} مشاريع • {{ count($dashboard->profile['experiences'] ?? []) }} خبرات</p>
            @else
            <x-rf-empty-state title="لم يتم إنشاء الملف بعد" />
            @endif
        </x-rf-card>

        <x-rf-card>
            <x-slot:title>المهارات</x-slot:title>
            @if($dashboard->skillProfile)
            <p class="text-sm">{{ count($dashboard->skillProfile['skills'] ?? []) }} مهارة • {{ count($dashboard->skillProfile['certifications'] ?? []) }} شهادة</p>
            @else
            <x-rf-empty-state title="لم يتم إضافة مهارات بعد" />
            @endif
        </x-rf-card>

        <x-rf-card>
            <x-slot:title>الفرص</x-slot:title>
            <p class="text-sm">{{ count($dashboard->opportunities['saved'] ?? []) }} محفوظة • {{ count($dashboard->opportunities['applications'] ?? []) }} متقدم</p>
        </x-rf-card>
    </div>
</div>
@endsection
