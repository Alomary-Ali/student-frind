@extends('layouts.dashboard')

@section('title', 'مركز الخدمات الطلابية')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">الخدمات الطلابية</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">مركز الخدمات الطلابية</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">قدم طلبات الخدمات، تابع حالة معاملاتك، واستفد من المساعد الذكي.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-rf-kpi-card title="الطلبات النشطة" :value="$dashboard->activeRequests" variant="primary" class="w-full">
            <p class="text-xs mt-2">الطلبات قيد المعالجة حالياً</p>
        </x-rf-kpi-card>

        <x-rf-kpi-card title="المستندات قيد الإنشاء" :value="$dashboard->pendingDocuments" variant="warning" class="w-full">
            <p class="text-xs mt-2">المستندات التي لم تصدر بعد</p>
        </x-rf-kpi-card>

        <x-rf-kpi-card title="الإشعارات غير المقروءة" :value="$dashboard->unreadNotifications" variant="accent" class="w-full">
            <p class="text-xs mt-2">آخر التحديثات على طلباتك</p>
        </x-rf-kpi-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-rf-card>
            <div class="flex items-center justify-between mb-4">
                <x-slot:title>آخر الطلبات</x-slot:title>
                <a href="{{ route('student-services.requests.index') }}" class="text-xs font-semibold text-primary hover:underline">عرض الكل</a>
            </div>
            @forelse($dashboard->recentRequests as $request)
            <div class="flex justify-between items-center py-3 border-b last:border-0" style="border-color: hsl(var(--color-border-light));">
                <div>
                    <p class="text-sm font-medium">{{ $request->refNumber }}</p>
                    <p class="text-xs mt-1">{{ $request->createdAt }}</p>
                </div>
                <x-rf-badge variant="{{ $request->status === 'completed' ? 'accent' : ($request->status === 'rejected' ? 'danger' : ($request->status === 'cancelled' ? 'secondary' : 'warning')) }}">
                    {{ \Modules\StudentServices\Domain\Enums\ServiceStatus::tryFrom($request->status)?->label() ?? $request->status }}
                </x-rf-badge>
            </div>
            @empty
            <x-rf-empty-state title="لا توجد طلبات حديثة" description="قم بتقديم طلب خدمة جديد للبدء" />
            @endforelse
        </x-rf-card>

        <x-rf-card>
            <x-slot:title>الخدمات السريعة</x-slot:title>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('student-services.requests.create') }}" class="block p-4 rounded-2xl border text-center hover:shadow-md transition-all" style="border-color: hsl(var(--color-border));">
                    <svg class="w-8 h-8 mx-auto text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <p class="text-xs font-bold mt-2">طلب خدمة جديدة</p>
                </a>
                <a href="{{ route('student-services.documents.index') }}" class="block p-4 rounded-2xl border text-center hover:shadow-md transition-all" style="border-color: hsl(var(--color-border));">
                    <svg class="w-8 h-8 mx-auto text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-xs font-bold mt-2">طلب مستند</p>
                </a>
                <a href="{{ route('student-services.knowledge.index') }}" class="block p-4 rounded-2xl border text-center hover:shadow-md transition-all" style="border-color: hsl(var(--color-border));">
                    <svg class="w-8 h-8 mx-auto text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-xs font-bold mt-2">مركز المعرفة</p>
                </a>
                <a href="{{ route('student-services.assistant.chat') }}" class="block p-4 rounded-2xl border text-center hover:shadow-md transition-all" style="border-color: hsl(var(--color-border));">
                    <svg class="w-8 h-8 mx-auto text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <p class="text-xs font-bold mt-2">المساعد الذكي</p>
                </a>
            </div>
        </x-rf-card>
    </div>

    <x-rf-card>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: var(--gradient-navy);">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold">المساعد الذكي للخدمات الطلابية</h3>
                <p class="text-xs mt-1">اسأل عن الخدمات، تعرف على إجراءات تقديم الطلبات، واحصل على إجابات فورية.</p>
            </div>
            <a href="{{ route('student-services.assistant.chat') }}" class="btn btn-primary btn-sm shrink-0">فتح المساعد</a>
        </div>
    </x-rf-card>
</div>
@endsection
