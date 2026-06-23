@extends('layouts.dashboard')

@section('title', 'مقررات المنهج')

@section('content')
@if($error)
    <div class="rounded-lg p-4 mb-6" style="background:hsl(var(--color-error)/0.10);border:1px solid hsl(var(--color-error)/0.20);">
        <p style="color:hsl(var(--color-error));">{{ $error }}</p>
    </div>
@else
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color:hsl(var(--color-text-primary));">مقررات المنهج</h1>
        <p class="mt-1" style="color:hsl(var(--color-text-muted));">جميع مقررات المنهج الدراسي مع حالاتها</p>
    </div>

    @if(count($courses) > 0)
        <div class="p-0 rounded-xl border border-border bg-surface shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                <thead style="background:hsl(var(--color-background));">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">رمز المقرر</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">اسم المقرر</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">الساعات المعتمدة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:hsl(var(--color-text-muted));">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr style="border-bottom:1px solid hsl(var(--color-border));">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color:hsl(var(--color-text-primary));">{{ $course['code'] ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color:hsl(var(--color-text-muted));">{{ $course['name'] ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color:hsl(var(--color-text-muted));">{{ $course['credit_hours'] ?? 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($course['status'] === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:hsl(var(--color-accent)/0.10);color:hsl(var(--color-accent));">مكتمل</span>
                            @elseif($course['status'] === 'in_progress')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:hsl(var(--color-primary)/0.10);color:hsl(var(--color-primary));">قيد التنفيذ</span>
                            @elseif($course['status'] === 'not_started')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:hsl(var(--color-background));color:hsl(var(--color-text-muted));">لم يبدأ</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:hsl(var(--color-error)/0.10);color:hsl(var(--color-error));">غير متاح</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    @else
        <div class="p-12 rounded-xl border border-border bg-surface shadow-sm text-center">
            <svg class="h-12 w-12 mx-auto mb-4" style="color:hsl(var(--color-text-muted));" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <p class="text-sm font-semibold" style="color:hsl(var(--color-text-primary));">لا توجد مقررات في المنهج</p>
            <p class="text-xs mt-1" style="color:hsl(var(--color-text-muted));">يرجى التواصل مع الإدارة</p>
        </div>
    @endif
@endif
@endsection
