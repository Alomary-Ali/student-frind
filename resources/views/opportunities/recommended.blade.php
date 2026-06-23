@php
declare(strict_types=1);
@endphp

@extends('layouts.dashboard')

@section('title', 'الفرص الموصى بها')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">الفرص الموصى بها</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">فرص مختارة خصيصاً بناءً على ملفك الشخصي ومهاراتك</p>
        </div>

        @if(empty($recommendations))
            <x-rf-empty-state
                icon="star"
                title="لا توجد توصيات بعد"
                description="قم بإنشاء ملفك المهني وإضافة مهاراتك للحصول على توصيات مخصصة"
                actionText="إنشاء الملف المهني"
                :actionUrl="route('career.index')"
            />
        @else
            <div class="space-y-3">
                @foreach($recommendations as $rec)
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $rec->opportunity->title ?? 'فرصة' }}</h3>
                                    <x-rf-badge variant="success" size="sm">{{ $rec->score }}%</x-rf-badge>
                                </div>
                                @if($rec->reason)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $rec->reason }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('opportunities.save') }}">
                                    @csrf
                                    <input type="hidden" name="opportunity_id" value="{{ $rec->opportunityId }}">
                                    <x-rf-button type="submit" variant="secondary" size="sm">حفظ</x-rf-button>
                                </form>
                                <form method="POST" action="{{ route('opportunities.apply') }}">
                                    @csrf
                                    <input type="hidden" name="opportunity_id" value="{{ $rec->opportunityId }}">
                                    <x-rf-button type="submit" variant="primary" size="sm">تقدم</x-rf-button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
