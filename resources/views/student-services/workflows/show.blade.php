@extends('layouts.dashboard')

@section('title', 'تفاصيل سير العمل')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">سير العمل</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">{{ $workflow->name }}</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">تفاصيل خطوات سير العمل للخدمة.</p>
        </div>
    </div>

    <x-rf-card>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-text-muted">الحالة</p>
                    <x-rf-badge variant="{{ $workflow->status === 'active' ? 'accent' : 'secondary' }}">
                        {{ \Modules\StudentServices\Domain\Enums\WorkflowStatus::tryFrom($workflow->status)?->label() ?? $workflow->status }}
                    </x-rf-badge>
                </div>
                <div>
                    <p class="text-sm text-text-muted">عدد الخطوات</p>
                    <p class="font-medium">{{ $workflow->steps->count() }}</p>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <p class="text-sm text-text-muted mb-4">الخطوات</p>
                <div class="space-y-4">
                    @foreach($workflow->steps as $step)
                    <div class="p-4 rounded-xl border" style="border-color: hsl(var(--color-border)); background: hsl(var(--color-surface) / 0.5);">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $step->status === 'completed' ? 'bg-green-500 text-white' : ($step->status === 'in_progress' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                                @if($step->status === 'completed')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                @else
                                <span class="text-sm font-medium">{{ $loop->iteration }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium">{{ $step->name }}</p>
                                        <p class="text-xs text-text-muted mt-1">{{ \Modules\StudentServices\Domain\Enums\WorkflowStepType::tryFrom($step->type)?->label() ?? $step->type }}</p>
                                    </div>
                                    @if($step->assigneeRole)
                                    <x-rf-badge variant="secondary" class="text-xs">{{ $step->assigneeRole }}</x-rf-badge>
                                    @endif
                                </div>
                                @if($step->config)
                                <div class="mt-3 p-3 rounded-lg bg-background text-xs" style="border-color: hsl(var(--color-border));">
                                    <p class="text-text-muted mb-1">الإعدادات:</p>
                                    <pre class="text-text-muted overflow-auto">{{ json_decode($step->config, true) ? json_encode(json_decode($step->config), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $step->config }}</pre>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-rf-card>

    <a href="{{ route('student-services.services.index') }}" class="btn btn-secondary">عودة للخدمات</a>
</div>
@endsection
