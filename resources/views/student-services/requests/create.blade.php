@extends('layouts.dashboard')

@section('title', 'طلب خدمة جديدة')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">خدمة جديدة</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">طلب خدمة جديدة</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">اختر نوع الخدمة وقدم طلبك وسيتم معالجته من قبل الجهة المختصة.</p>
        </div>
    </div>

    <x-rf-card>
        <form method="POST" action="{{ route('student-services.requests.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">نوع الخدمة</label>
                <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                    <option value="">اختر الخدمة المطلوبة</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }} — {{ \Modules\StudentServices\Domain\Enums\ServiceCategoryType::tryFrom($category->type)?->label() ?? $category->type }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">الأولوية</label>
                <select name="priority" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                    <option value="low">منخفضة</option>
                    <option value="medium" selected>متوسطة</option>
                    <option value="high">عالية</option>
                    <option value="urgent">عاجلة</option>
                </select>
                @error('priority')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">ملاحظات إضافية (اختياري)</label>
                <textarea name="notes" rows="4" placeholder="اكتب أي ملاحظات أو تفاصيل إضافية تريد إرفاقها مع الطلب..." class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('student-services.requests.index') }}" class="btn btn-sm btn-ghost">إلغاء</a>
                <button type="submit" class="btn btn-sm btn-primary">تقديم الطلب</button>
            </div>
        </form>
    </x-rf-card>
</div>
@endsection
