@extends('layouts.dashboard')

@section('title', 'الملف الأكاديمي')

@section('content')

@if($error)
    <div class="rounded-2xl p-6 mb-6" style="background:hsl(var(--color-error)/0.10);border:1px solid hsl(var(--color-error)/0.20);">
        <p class="font-semibold" style="color:hsl(var(--color-error));">{{ $error }}</p>
    </div>
@elseif(!$profile)
    <div class="rounded-2xl p-16 text-center mb-6" style="background:hsl(var(--color-surface));border:1px solid hsl(var(--color-border));">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background:hsl(var(--color-background));">
            <svg class="h-10 w-10" style="color:hsl(var(--color-text-muted));" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2" style="color:hsl(var(--color-text-primary));">لا يوجد ملف أكاديمي</p>
        <p class="text-sm" style="color:hsl(var(--color-text-muted));">يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي</p>
    </div>
@else
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="flex items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-black" style="color:hsl(var(--color-text-primary));">الملف الأكاديمي</h1>
            <p class="text-sm mt-0.5" style="color:hsl(var(--color-text-muted));">عرض شامل للبيانات الأكاديمية الخاصة بك</p>
        </div>
    </div>

    {{-- Identity Card --}}
    <div class="p-0 rounded-xl border border-border bg-surface shadow-sm overflow-hidden mb-6">
        <div class="p-6 text-white" style="background:linear-gradient(135deg,hsl(var(--color-primary-hover)),hsl(var(--color-primary)));">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-black" style="background:hsl(var(--color-surface)/0.15);">
                    {{ auth()->user()->first_name ? mb_substr(auth()->user()->first_name, 0, 1) : 'S' }}
                </div>
                <div>
                    <h2 class="text-lg font-black">{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</h2>
                    <p class="text-sm" style="color:hsl(var(--color-surface)/0.70);">{{ $profile['student_number'] ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">الحالة الأكاديمية</p>
                        <p class="text-sm font-bold mt-0.5" style="color:hsl(var(--color-text-primary));">{{ $profile['academic_status'] ?? 'Active' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">الوضع الأكاديمي</p>
                        <p class="text-sm font-bold mt-0.5" style="color:hsl(var(--color-text-primary));">{{ $profile['academic_standing'] ?? 'Good Standing' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">المستوى الدراسي</p>
                        <p class="text-sm font-bold mt-0.5" style="color:hsl(var(--color-text-primary));">{{ $profile['level'] ?? '1' }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">المعدل التراكمي</p>
                        <p class="text-2xl font-black mt-0.5" style="color:hsl(var(--color-text-primary));">{{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">الساعات المنجزة</p>
                        <p class="text-sm font-bold mt-0.5" style="color:hsl(var(--color-text-primary));">{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }} / {{ $graduationProgress ? $graduationProgress['credits_required'] : 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">الساعات المتبقية</p>
                        <p class="text-sm font-bold mt-0.5" style="color:hsl(var(--color-warning));">{{ $graduationProgress ? max(0, $graduationProgress['credits_required'] - $graduationProgress['credits_earned']) : 0 }} ساعة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Graduation Progress Card --}}
    <div class="p-6 rounded-xl border border-border bg-surface shadow-sm mb-6">
        <h2 class="text-sm font-bold mb-4" style="color:hsl(var(--color-text-primary));">التقدم نحو التخرج</h2>
        <div class="progress-track mb-2" style="height: 10px;">
            <div class="progress-fill" style="width: {{ $graduationProgress ? $graduationProgress['completion_percentage'] : 0 }}%;background:linear-gradient(90deg,hsl(var(--color-primary)),hsl(var(--color-accent)));"></div>
        </div>
        <div class="flex items-center justify-between text-xs" style="color:hsl(var(--color-text-muted));">
            <span>{{ $graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) : 0 }}% مكتمل</span>
            <span>{{ $graduationProgress && $graduationProgress['estimated_graduation_date'] ? date('F Y', strtotime($graduationProgress['estimated_graduation_date'])) : 'غير محدد' }}</span>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 rounded-xl border border-border bg-surface shadow-sm text-center">
            <p class="text-2xl font-black" style="color:hsl(var(--color-accent));">{{ $graduationProgress ? $graduationProgress['credits_earned'] : 0 }}</p>
            <p class="text-[10px] mt-1" style="color:hsl(var(--color-text-muted));">ساعة مكتسبة</p>
        </div>
        <div class="p-4 rounded-xl border border-border bg-surface shadow-sm text-center">
            <p class="text-2xl font-black" style="color:hsl(var(--color-warning));">{{ $graduationProgress ? max(0, $graduationProgress['credits_required'] - $graduationProgress['credits_earned']) : 0 }}</p>
            <p class="text-[10px] mt-1" style="color:hsl(var(--color-text-muted));">ساعة متبقية</p>
        </div>
        <div class="p-4 rounded-xl border border-border bg-surface shadow-sm text-center">
            <p class="text-2xl font-black" style="color:hsl(var(--color-text-primary));">{{ number_format($profile['cumulative_gpa'] ?? 0, 2) }}</p>
            <p class="text-[10px] mt-1" style="color:hsl(var(--color-text-muted));">المعدل التراكمي</p>
        </div>
        <div class="p-4 rounded-xl border border-border bg-surface shadow-sm text-center">
            <p class="text-2xl font-black" style="color:hsl(var(--color-text-muted));">{{ $profile['level'] ?? '1' }}</p>
            <p class="text-[10px] mt-1" style="color:hsl(var(--color-text-muted));">المستوى الحالي</p>
        </div>
    </div>

    {{-- Academic Info Table --}}
    <div class="p-0 rounded-xl border border-border bg-surface shadow-sm overflow-hidden">
        <div class="p-4 border-b" style="background:hsl(var(--color-background));border-color:hsl(var(--color-border));">
            <h2 class="text-sm font-bold" style="color:hsl(var(--color-text-primary));">تفاصيل إضافية</h2>
        </div>
        <div class="p-4">
            <table class="w-full text-sm">
                <tbody>
                    <tr class="border-b" style="border-color:hsl(var(--color-border));">
                        <td class="py-3" style="color:hsl(var(--color-text-muted));">الرقم الجامعي</td>
                        <td class="py-3 font-semibold" style="color:hsl(var(--color-text-primary));">{{ $profile['student_number'] ?? '-' }}</td>
                    </tr>
                    <tr class="border-b" style="border-color:hsl(var(--color-border));">
                        <td class="py-3" style="color:hsl(var(--color-text-muted));">البريد الإلكتروني</td>
                        <td class="py-3 font-semibold" style="color:hsl(var(--color-text-primary));">{{ auth()->user()->email ?? '-' }}</td>
                    </tr>
                    <tr class="border-b" style="border-color:hsl(var(--color-border));">
                        <td class="py-3" style="color:hsl(var(--color-text-muted));">الحالة الأكاديمية</td>
                        <td class="py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold" style="background:hsl(var(--color-accent)/0.10);color:hsl(var(--color-accent));">{{ $profile['academic_status'] ?? 'Active' }}</span></td>
                    </tr>
                    <tr class="border-b" style="border-color:hsl(var(--color-border));">
                        <td class="py-3" style="color:hsl(var(--color-text-muted));">الوضع الأكاديمي</td>
                        <td class="py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold" style="background:hsl(var(--color-primary)/0.10);color:hsl(var(--color-primary));">{{ $profile['academic_standing'] ?? 'Good Standing' }}</span></td>
                    </tr>
                    <tr>
                        <td class="py-3" style="color:hsl(var(--color-text-muted));">تاريخ التسجيل في النظام</td>
                        <td class="py-3 font-semibold" style="color:hsl(var(--color-text-primary));">{{ $profile['created_at'] ? date('d M Y', strtotime($profile['created_at'])) : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
