@extends('layouts.dashboard')

@section('title', 'الأسئلة الشائعة')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">الأسئلة الشائعة</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">الأسئلة الشائعة</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">إجابات لأكثر الأسئلة شيوعاً حول الخدمات الطلابية.</p>
        </div>
    </div>

    @if(!empty($categories))
    <div class="flex flex-wrap gap-2">
        <button onclick="filterFaq('all')" class="btn btn-sm btn-primary" id="faq-filter-all">الكل</button>
        @foreach($categories as $cat)
        <button onclick="filterFaq('{{ $cat->id }}')" class="btn btn-sm btn-ghost" id="faq-filter-{{ $cat->id }}">{{ $cat->name }}</button>
        @endforeach
    </div>
    @endif

    <div class="space-y-3 faq-list">
        @forelse($faqs as $faq)
        <div class="faq-item card p-0 overflow-hidden" data-category="{{ $faq->categoryId }}">
            <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-4 text-right hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <span class="text-sm font-medium">{{ $faq->question }}</span>
                <svg class="w-5 h-5 shrink-0 mr-2 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="faq-answer hidden px-4 pb-4">
                <div class="text-sm leading-relaxed pt-2 border-t" style="border-color: hsl(var(--color-border-light));">
                    {{ $faq->answer }}
                </div>
            </div>
        </div>
        @empty
        <x-rf-empty-state title="لا توجد أسئلة شائعة بعد" description="سيتم إضافة الأسئلة الشائعة قريباً" />
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleFaq(btn) {
        const answer = btn.nextElementSibling;
        const icon = btn.querySelector('svg');
        const isHidden = answer.classList.contains('hidden');
        answer.classList.toggle('hidden');
        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function filterFaq(categoryId) {
        document.querySelectorAll('[id^="faq-filter-"]').forEach(btn => {
            btn.className = 'btn btn-sm btn-ghost';
        });
        const activeBtn = document.getElementById('faq-filter-' + categoryId);
        if (activeBtn) activeBtn.className = 'btn btn-sm btn-primary';
        if (categoryId === 'all') {
            document.getElementById('faq-filter-all').className = 'btn btn-sm btn-primary';
        }

        document.querySelectorAll('.faq-item').forEach(item => {
            if (categoryId === 'all' || item.dataset.category === categoryId) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endpush
