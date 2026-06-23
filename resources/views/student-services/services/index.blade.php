@extends('layouts.dashboard')

@section('title', 'الخدمات المتاحة')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">كتالوج الخدمات</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">الخدمات المتاحة</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">تصفح جميع الخدمات الطلابية المتاحة وقدم طلبك بكل سهولة.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
        <a href="{{ route('student-services.requests.create', ['category' => $service->id]) }}" class="block no-underline">
            <x-rf-card class="h-full">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background: var(--gradient-navy);">
                        <span class="text-white font-bold text-sm">{{ substr($service->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <x-slot:title>{{ $service->name }}</x-slot:title>
                        <x-rf-badge variant="{{ $service->type === 'academic' ? 'primary' : ($service->type === 'document' ? 'accent' : ($service->type === 'financial' ? 'warning' : 'secondary')) }}" class="mt-1">
                            {{ \Modules\StudentServices\Domain\Enums\ServiceCategoryType::tryFrom($service->type)?->label() ?? $service->type }}
                        </x-rf-badge>
                    </div>
                </div>
                <p class="text-sm leading-relaxed">{{ $service->description }}</p>
            </x-rf-card>
        </a>
        @empty
        <div class="col-span-full">
            <x-rf-empty-state title="لا توجد خدمات متاحة حالياً" description="سيتم إضافة الخدمات قريباً" />
        </div>
        @endforelse
    </div>
</div>
@endsection
