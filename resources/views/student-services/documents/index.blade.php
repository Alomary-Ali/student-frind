@extends('layouts.dashboard')

@section('title', 'المستندات')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">المستندات</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">المستندات</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">جميع المستندات الصادرة الخاصة بك.</p>
            </div>
            <a href="{{ route('student-services.documents.create') }}" class="btn btn-primary btn-sm">+ طلب مستند جديد</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $document)
        <x-rf-card>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background: var(--gradient-navy);">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <x-slot:title>{{ $document->title }}</x-slot:title>
                    <x-rf-badge variant="{{ $document->status === 'generated' ? 'accent' : ($document->status === 'verified' ? 'primary' : ($document->status === 'expired' ? 'secondary' : 'warning')) }}" class="mt-1">
                        {{ \Modules\StudentServices\Domain\Enums\DocumentStatus::tryFrom($document->status)?->label() ?? $document->status }}
                    </x-rf-badge>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-text-muted">النوع</span>
                    <span>{{ \Modules\StudentServices\Domain\Enums\DocumentType::tryFrom($document->type)?->label() ?? $document->type }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-text-muted">تاريخ الإنشاء</span>
                    <span>{{ $document->createdAt }}</span>
                </div>
            </div>
            @if($document->status === 'generated' && $document->filePath)
            <a href="{{ asset($document->filePath) }}" target="_blank" class="btn btn-primary btn-sm w-full mt-4">تحميل المستند</a>
            @endif
        </x-rf-card>
        @empty
        <div class="col-span-full">
            <x-rf-empty-state title="لا توجد مستندات بعد" description="المستندات التي تطلبها ستظهر هنا">
                <a href="{{ route('student-services.documents.create') }}" class="btn btn-primary btn-sm mt-4">طلب مستند جديد</a>
            </x-rf-empty-state>
        </div>
        @endforelse
    </div>
</div>
@endsection
