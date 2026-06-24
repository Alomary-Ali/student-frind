@extends('layouts.dashboard')

@section('title', 'طلب مستند جديد')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">المستندات</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">طلب مستند جديد</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">اختر نوع المستند المطلوب وقدم طلبك.</p>
        </div>
    </div>

    <x-rf-card>
        <form method="POST" action="{{ route('student-services.documents.request') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">نوع المستند</label>
                <select name="document_type" required class="w-full rounded-xl border px-4 py-3 bg-background" style="border-color: hsl(var(--color-border));">
                    <option value="">اختر نوع المستند</option>
                    <option value="certificate">شهادة</option>
                    <option value="transcript">كشف درجات</option>
                    <option value="statement">بيان</option>
                    <option value="official_letter">خطاب رسمي</option>
                    <option value="id_card">بطاقة هوية</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">ملاحظات (اختياري)</label>
                <textarea name="notes" rows="4" class="w-full rounded-xl border px-4 py-3 bg-background" style="border-color: hsl(var(--color-border));" placeholder="أي ملاحظات إضافية..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1">تقديم الطلب</button>
                <a href="{{ route('student-services.documents.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </x-rf-card>
</div>
@endsection
