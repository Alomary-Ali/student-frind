@php
declare(strict_types=1);
@endphp

@extends('layouts.dashboard')

@section('title', 'مركز الفرص')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">مركز الفرص</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">اكتشف الفرص المناسبة لك وتقدم إليها</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @php
                $sections = [
                    ['label' => 'الوظائف', 'count' => count($jobs ?? []), 'route' => 'opportunities.jobs', 'color' => 'primary'],
                    ['label' => 'التدريب', 'count' => count($internships ?? []), 'route' => 'opportunities.internships', 'color' => 'success'],
                    ['label' => 'المنح الدراسية', 'count' => count($scholarships ?? []), 'route' => 'opportunities.scholarships', 'color' => 'warning'],
                    ['label' => 'الدورات', 'count' => count($courses ?? []), 'route' => 'opportunities.courses', 'color' => 'info'],
                    ['label' => 'المسابقات', 'count' => count($competitions ?? []), 'route' => 'opportunities.competitions', 'color' => 'danger'],
                    ['label' => 'التطوع', 'count' => count($volunteering ?? []), 'route' => 'opportunities.index', 'color' => 'neutral'],
                    ['label' => 'المؤتمرات', 'count' => count($conferences ?? []), 'route' => 'opportunities.index', 'color' => 'info'],
                ];
            @endphp

            @foreach($sections as $section)
                <a href="{{ route($section['route']) }}"
                   class="block p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $section['label'] }}</div>
                    <div class="mt-2 text-3xl font-bold text-primary">{{ $section['count'] }}</div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">فرصة متاحة</div>
                </a>
            @endforeach
        </div>

        @if(!empty($opportunities))
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">جميع الفرص</h2>
                <div class="space-y-3">
                    @foreach($opportunities as $opp)
                        <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $opp->title }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $opp->description }}</p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                        <span>{{ $opp->type }}</span>
                                        @if($opp->deadline)
                                            <span>آخر موعد: {{ $opp->deadline }}</span>
                                        @endif
                                        @if($opp->country)
                                            <span>{{ $opp->country }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form method="POST" action="{{ route('opportunities.save') }}">
                                        @csrf
                                        <input type="hidden" name="opportunity_id" value="{{ $opp->id }}">
                                        <x-rf-button type="submit" variant="secondary" size="sm">حفظ</x-rf-button>
                                    </form>
                                    <form method="POST" action="{{ route('opportunities.apply') }}">
                                        @csrf
                                        <input type="hidden" name="opportunity_id" value="{{ $opp->id }}">
                                        <input type="hidden" name="notes" value="">
                                        <x-rf-button type="submit" variant="primary" size="sm">تقدم الآن</x-rf-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
