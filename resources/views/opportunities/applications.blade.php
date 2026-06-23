@php
declare(strict_types=1);
@endphp

@extends('layouts.dashboard')

@section('title', 'طلبات التقديم')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">طلبات التقديم</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">تابع حالة طلبات التقديم التي قدمتها</p>
        </div>

        @if(empty($applications))
            <x-rf-empty-state
                icon="document"
                title="لا توجد طلبات تقديم"
                description="لم تقدم على أي فرصة بعد. استعرض الفرص المتاحة وابدأ بالتقديم"
                actionText="استعرض الفرص"
                :actionUrl="route('opportunities.index')"
            />
        @else
            <div class="space-y-3">
                @foreach($applications as $app)
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">معرف الفرصة: {{ $app->opportunityId }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @if($app->appliedAt)
                                        تاريخ التقديم: {{ $app->appliedAt }}
                                    @endif
                                </p>
                                @if($app->notes)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $app->notes }}</p>
                                @endif
                            </div>
                            <x-rf-badge variant="{{ $app->status === 'accepted' ? 'success' : ($app->status === 'rejected' ? 'danger' : ($app->status === 'in_review' ? 'warning' : 'info')) }}">
                                {{ $app->status }}
                            </x-rf-badge>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
