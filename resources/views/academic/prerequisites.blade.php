@extends('layouts.dashboard')

@section('title', 'المتطلبات السابقة')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color:hsl(var(--color-text-primary));">المتطلبات السابقة</h1>
        <p class="mt-1" style="color:hsl(var(--color-text-muted));">المتطلبات السابقة لكل مقرر دراسي</p>
    </div>

    @if(isset($prerequisites) && count($prerequisites) > 0)
        <div class="space-y-4">
            @foreach($prerequisites as $courseCode => $coursePrerequisites)
            <div class="unit-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold" style="color:hsl(var(--color-text-primary));">{{ $courseCode }}</h2>
                        <p class="text-sm" style="color:hsl(var(--color-text-muted));">{{ $coursePrerequisites['course_name'] ?? '-' }}</p>
                    </div>
                    <x-rf-badge variant="primary">{{ count($coursePrerequisites['prerequisites'] ?? []) }} متطلب</x-rf-badge>
                </div>

                @if(isset($coursePrerequisites['prerequisites']) && count($coursePrerequisites['prerequisites']) > 0)
                    <div class="space-y-2">
                        @foreach($coursePrerequisites['prerequisites'] as $prerequisite)
                        <div class="flex items-center justify-between p-3 rounded-lg" style="background:hsl(var(--color-background));">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full" style="background:{{ $prerequisite['is_required'] ? 'hsl(var(--color-error))' : 'hsl(var(--color-warning))' }};"></div>
                                <div>
                                    <p class="text-sm font-semibold" style="color:hsl(var(--color-text-primary));">{{ $prerequisite['prerequisite_code'] ?? '-' }}</p>
                                    <p class="text-xs" style="color:hsl(var(--color-text-muted));">{{ $prerequisite['prerequisite_name'] ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="text-left">
                                @if($prerequisite['is_required'])
                                    <span class="text-xs font-semibold" style="color:hsl(var(--color-error));">إلزامي</span>
                                @else
                                    <span class="text-xs font-semibold" style="color:hsl(var(--color-warning));">اختياري</span>
                                @endif
                                @if(isset($prerequisite['minimum_grade']))
                                    <p class="text-xs" style="color:hsl(var(--color-text-muted));">الحد الأدنى: {{ $prerequisite['minimum_grade'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm" style="color:hsl(var(--color-text-muted));">لا توجد متطلبات سابقة لهذا المقرر</p>
                @endif
            </div>
            @endforeach
        </div>
    @else
        <div class="unit-card p-12 text-center">
            <svg class="h-12 w-12 mx-auto mb-4" style="color:hsl(var(--color-text-muted));" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <p class="text-sm font-semibold" style="color:hsl(var(--color-text-primary));">لا توجد بيانات المتطلبات</p>
            <p class="text-xs mt-1" style="color:hsl(var(--color-text-muted));">يرجى التواصل مع الإدارة</p>
        </div>
    @endif
@endsection
