@extends('layouts.dashboard')

@section('title', 'طلباتي')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">الطلبات</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">طلباتي</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">تابع حالة طلبات الخدمات التي قدمتها.</p>
            </div>
            <a href="{{ route('student-services.requests.create') }}" class="btn btn-primary btn-sm">+ طلب جديد</a>
        </div>
    </div>

    <form method="GET" class="flex gap-3">
        <select name="status" onchange="this.form.submit()" class="rounded-xl border px-4 py-2 text-sm bg-background" style="border-color: hsl(var(--color-border));">
            <option value="">جميع الحالات</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>جديد</option>
            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
        </select>
    </form>

    <div class="space-y-4">
        @forelse($requests as $request)
        <a href="{{ route('student-services.requests.show', $request->id) }}" class="block no-underline">
            <x-rf-card>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="font-medium text-sm">{{ $request->refNumber }}</p>
                            <p class="text-xs mt-1">{{ $request->createdAt }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-rf-badge variant="{{ $request->priority === 'urgent' ? 'danger' : ($request->priority === 'high' ? 'warning' : 'secondary') }}">
                            {{ \Modules\StudentServices\Domain\Enums\RequestPriority::tryFrom($request->priority)?->label() ?? $request->priority }}
                        </x-rf-badge>
                        <x-rf-badge variant="{{ $request->status === 'completed' ? 'accent' : ($request->status === 'rejected' || $request->status === 'cancelled' ? 'secondary' : ($request->status === 'approved' ? 'primary' : 'warning')) }}">
                            {{ \Modules\StudentServices\Domain\Enums\ServiceStatus::tryFrom($request->status)?->label() ?? $request->status }}
                        </x-rf-badge>
                    </div>
                </div>
            </x-rf-card>
        </a>
        @empty
        <x-rf-empty-state title="لا توجد طلبات حالياً" description="يمكنك تقديم طلب خدمة جديد من هنا">
            <a href="{{ route('student-services.requests.create') }}" class="btn btn-primary btn-sm mt-4">تقديم طلب جديد</a>
        </x-rf-empty-state>
        @endforelse
    </div>
</div>
@endsection
