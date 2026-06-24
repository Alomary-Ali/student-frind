@extends('layouts.dashboard')

@section('title', 'تتبع حالة الطلب')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">الطلبات</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">تتبع حالة الطلب</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">رقم الطلب: {{ $request->refNumber }}</p>
        </div>
    </div>

    <x-rf-card>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-text-muted">رقم الطلب</p>
                    <p class="font-medium">{{ $request->refNumber }}</p>
                </div>
                <div>
                    <p class="text-sm text-text-muted">تاريخ التقديم</p>
                    <p class="font-medium">{{ $request->createdAt }}</p>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <p class="text-sm text-text-muted mb-2">الحالة الحالية</p>
                <x-rf-badge variant="{{ $request->status === 'completed' ? 'accent' : ($request->status === 'rejected' || $request->status === 'cancelled' ? 'secondary' : ($request->status === 'approved' ? 'primary' : 'warning')) }}" class="text-lg">
                    {{ \Modules\StudentServices\Domain\Enums\ServiceStatus::tryFrom($request->status)?->label() ?? $request->status }}
                </x-rf-badge>
            </div>

            @if($request->notes)
            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <p class="text-sm text-text-muted mb-2">ملاحظات الطلب</p>
                <p class="text-sm">{{ $request->notes }}</p>
            </div>
            @endif

            @if($request->adminNotes)
            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <p class="text-sm text-text-muted mb-2">ملاحظات الإدارة</p>
                <p class="text-sm">{{ $request->adminNotes }}</p>
            </div>
            @endif

            @if($workflow)
            <div class="border-t pt-6" style="border-color: hsl(var(--color-border));">
                <p class="text-sm text-text-muted mb-4">تقدم سير العمل</p>
                <div class="space-y-3">
                    @foreach($workflow->steps as $step)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $step->status === 'completed' ? 'bg-green-500 text-white' : ($step->status === 'in_progress' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                            @if($step->status === 'completed')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            @else
                            <span class="text-xs">{{ $loop->iteration }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $step->name }}</p>
                            <p class="text-xs text-text-muted">{{ $step->type }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </x-rf-card>

    <div class="flex gap-3">
        <a href="{{ route('student-services.requests.index') }}" class="btn btn-secondary">عودة للطلبات</a>
        @if($request->status === 'new' || $request->status === 'under_review')
        <form method="POST" action="{{ route('student-services.requests.cancel', $request->id) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">إلغاء الطلب</button>
        </form>
        @endif
    </div>
</div>
@endsection
