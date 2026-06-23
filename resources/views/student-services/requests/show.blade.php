@extends('layouts.dashboard')

@section('title', 'تفاصيل الطلب - ' . $request->refNumber)

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">رقم الطلب: {{ $request->refNumber }}</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">تفاصيل الطلب</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">جميع تفاصيل طلب الخدمة والحالة الحالية.</p>
        </div>
    </div>

    {{-- Status Timeline --}}
    <x-rf-card>
        <x-slot:title>حالة الطلب</x-slot:title>
        <div class="space-y-4">
            @php
                $statuses = ['new', 'under_review', 'approved', 'completed'];
                $currentIndex = array_search($request->status, $statuses);
                $isRejectedCancelled = in_array($request->status, ['rejected', 'cancelled']);
            @endphp

            @if($isRejectedCancelled)
            <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <p class="text-sm font-bold text-red-600 dark:text-red-400">
                    @if($request->status === 'rejected')
                    تم رفض الطلب
                    @else
                    تم إلغاء الطلب
                    @endif
                </p>
            </div>
            @endif

            <div class="space-y-2">
                @foreach($statuses as $i => $status)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $i <= $currentIndex ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-400' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            @if($i < $currentIndex)
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            @elseif($i === $currentIndex)
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @else
                            <circle cx="12" cy="12" r="9" />
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium {{ $i <= $currentIndex ? 'text-text-primary' : 'text-text-muted' }}">
                            {{ \Modules\StudentServices\Domain\Enums\ServiceStatus::tryFrom($status)?->label() ?? $status }}
                        </p>
                    </div>
                </div>
                @if($i < count($statuses) - 1)
                <div class="w-0.5 h-6 mr-4 {{ $i < $currentIndex ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                @endif
                @endforeach
            </div>
        </div>
    </x-rf-card>

    {{-- Request Details --}}
    <x-rf-card>
        <x-slot:title>معلومات الطلب</x-slot:title>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-[10px] section-label">رقم المرجع</span>
                <p class="text-sm font-bold mt-1">{{ $request->refNumber }}</p>
            </div>
            <div>
                <span class="text-[10px] section-label">الأولوية</span>
                <p class="text-sm font-bold mt-1">
                    <x-rf-badge variant="{{ $request->priority === 'urgent' ? 'danger' : ($request->priority === 'high' ? 'warning' : 'secondary') }}">
                        {{ \Modules\StudentServices\Domain\Enums\RequestPriority::tryFrom($request->priority)?->label() ?? $request->priority }}
                    </x-rf-badge>
                </p>
            </div>
            <div>
                <span class="text-[10px] section-label">تاريخ الإنشاء</span>
                <p class="text-sm mt-1">{{ $request->createdAt }}</p>
            </div>
            <div>
                <span class="text-[10px] section-label">آخر تحديث</span>
                <p class="text-sm mt-1">{{ $request->updatedAt }}</p>
            </div>
        </div>

        @if($request->notes)
        <div class="mt-6">
            <span class="text-[10px] section-label">ملاحظاتك</span>
            <p class="text-sm mt-1 p-3 rounded-xl" style="background: hsl(var(--color-background));">{{ $request->notes }}</p>
        </div>
        @endif

        @if($request->adminNotes)
        <div class="mt-6">
            <span class="text-[10px] section-label">ملاحظات الإدارة</span>
            <p class="text-sm mt-1 p-3 rounded-xl" style="background: hsl(var(--color-surface) / 0.1);">{{ $request->adminNotes }}</p>
        </div>
        @endif
    </x-rf-card>

    {{-- Workflow Steps --}}
    @if(!empty($workflowSteps))
    <x-rf-card>
        <x-slot:title>خطوات سير العمل</x-slot:title>
        <div class="space-y-3">
            @foreach($workflowSteps as $step)
            <div class="flex items-center gap-3 p-3 rounded-xl {{ $step->id === $request->currentStepId ? 'bg-primary/10 border border-primary/20' : '' }}" style="border-color: hsl(var(--color-border-light));">
                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $step->status === 'completed' ? 'bg-green-100 dark:bg-green-900/30 text-green-600' : ($step->id === $request->currentStepId ? 'bg-primary/20 text-primary' : 'bg-gray-100 dark:bg-gray-800 text-gray-400') }}">
                    <span class="text-xs font-bold">{{ $step->order }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium">{{ $step->name }}</p>
                    <p class="text-xs mt-0.5">{{ \Modules\StudentServices\Domain\Enums\WorkflowStepType::tryFrom($step->type)?->label() ?? $step->type }}</p>
                </div>
                @if($step->status === 'completed')
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                @elseif($step->id === $request->currentStepId)
                <span class="text-xs font-bold text-primary">الحالي</span>
                @endif
            </div>
            @endforeach
        </div>
    </x-rf-card>
    @endif

    {{-- Actions --}}
    @if(in_array($request->status, ['new', 'under_review']))
    <div class="flex gap-3">
        <form method="POST" action="{{ route('student-services.requests.cancel', $request->id) }}" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">إلغاء الطلب</button>
        </form>
    </div>
    @endif

    <a href="{{ route('student-services.requests.index') }}" class="text-sm font-semibold text-primary hover:underline inline-flex items-center gap-1">
        &larr; العودة إلى قائمة الطلبات
    </a>
</div>
@endsection
