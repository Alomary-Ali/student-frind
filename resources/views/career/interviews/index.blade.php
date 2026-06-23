@extends('layouts.dashboard')

@section('title', 'المقابلات')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <x-rf-badge variant="accent" class="mb-3">التحضير للمقابلات</x-rf-badge>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">المقابلات الوظيفية</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">حضر للمقابلات، تدرب على الأسئلة، وحسن أدائك.</p>
            </div>
            <button onclick="openModal('schedule-interview-modal')" class="btn btn-primary btn-sm">+ مقابلة جديدة</button>
        </div>
    </div>

    @forelse($interviews as $interview)
    <x-rf-card>
        <div class="flex justify-between items-center">
            <div>
                <h3 class="font-medium">{{ __("career.interview_type.{$interview->type}") }}</h3>
                <p class="text-xs">{{ $interview->scheduledAt }}</p>
            </div>
            <x-rf-badge>{{ __("career.interview_status.{$interview->status}") }}</x-rf-badge>
        </div>
        @if($interview->score !== null)
        <div class="mt-2">
            <x-rf-progress :value="$interview->score" />
        </div>
        @endif
    </x-rf-card>
    @empty
    <x-rf-empty-state title="لا توجد مقابلات بعد" description="احجز أول مقابلة تجريبية للبدء" />
    @endforelse
</div>

{{-- Schedule Modal --}}
<div id="schedule-interview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">حجز مقابلة جديدة</h2>
        <form method="POST" action="{{ route('career.interviews.schedule') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">نوع المقابلة</label>
                    <select name="type" class="w-full rounded-xl border px-4 py-2 text-sm" required>
                        <option value="mock">تجريبية</option>
                        <option value="technical">تقنية</option>
                        <option value="behavioral">سلوكية</option>
                        <option value="general">عامة</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">التاريخ والوقت</label>
                    <input type="datetime-local" name="scheduled_at" class="w-full rounded-xl border px-4 py-2 text-sm" required />
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary flex-1">حجز</button>
                <button type="button" onclick="closeModal('schedule-interview-modal')" class="btn btn-ghost flex-1">إلغاء</button>
            </div>
        </form>
    </div>
</div>
@endsection
