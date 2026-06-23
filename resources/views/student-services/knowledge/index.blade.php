@extends('layouts.dashboard')

@section('title', 'مركز المعرفة')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">مركز المعرفة</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">مركز المعرفة</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">تصفح المقالات والدلائل الإرشادية للخدمات الطلابية.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('student-services.knowledge.search') }}" class="flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث في مركز المعرفة..." class="flex-1 rounded-xl border px-4 py-2.5 text-sm bg-background" style="border-color: hsl(var(--color-border));">
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>

    @if(!empty($categories))
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('student-services.knowledge.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-ghost' }}">الكل</a>
        @foreach($categories as $category)
        <a href="{{ route('student-services.knowledge.index', ['category' => $category->id]) }}" class="btn btn-sm {{ request('category') == $category->id ? 'btn-primary' : 'btn-ghost' }}">{{ $category->name }}</a>
        @endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <a href="{{ route('student-services.knowledge.show', $article->id) }}" class="block no-underline">
            <x-rf-card class="h-full">
                <x-slot:title>{{ $article->title }}</x-slot:title>
                <x-rf-badge variant="primary" class="mb-3">{{ $article->categoryId }}</x-rf-badge>
                <p class="text-sm leading-relaxed">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                <div class="flex items-center gap-2 mt-4 text-xs text-text-muted">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ $article->viewCount }} مشاهدة</span>
                </div>
            </x-rf-card>
        </a>
        @empty
        <div class="col-span-full">
            <x-rf-empty-state title="@if(request('q'))لا توجد نتائج للبحث@elseلا توجد مقالات بعد@endif" description="@if(request('q'))حاول استخدام كلمات بحث مختلفة@elseسيتم إضافة المقالات قريباً@endif" />
        </div>
        @endforelse
    </div>
</div>
@endsection
