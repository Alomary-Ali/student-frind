@php
declare(strict_types=1);
@endphp

@extends('layouts.dashboard')

@section('title', 'الفرص المحفوظة')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">الفرص المحفوظة</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">الفرص التي قمت بحفظها لمتابعتها لاحقاً</p>
        </div>

        @if(empty($opportunities))
            <x-rf-empty-state
                icon="bookmark"
                title="لا توجد فرص محفوظة"
                description="استعرض الفرص المتاحة واحفظ ما يناسبك"
                actionText="استعرض الفرص"
                :actionUrl="route('opportunities.index')"
            />
        @else
            <div class="space-y-3">
                @foreach($opportunities as $opp)
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $opp->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $opp->description }}</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                    <x-rf-badge variant="info" size="sm">{{ $opp->type }}</x-rf-badge>
                                    @if($opp->deadline)
                                        <span>آخر موعد: {{ $opp->deadline }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('opportunities.save') }}">
                                    @csrf
                                    <input type="hidden" name="opportunity_id" value="{{ $opp->id }}">
                                    <x-rf-button type="submit" variant="danger" size="sm">إلغاء الحفظ</x-rf-button>
                                </form>
                                <form method="POST" action="{{ route('opportunities.apply') }}">
                                    @csrf
                                    <input type="hidden" name="opportunity_id" value="{{ $opp->id }}">
                                    <x-rf-button type="submit" variant="primary" size="sm">تقدم الآن</x-rf-button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
