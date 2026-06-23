@extends('layouts.dashboard')

@section('title', $article->title)

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">مركز المعرفة</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">{{ $article->title }}</h1>
        </div>
    </div>

    <x-rf-card>
        <x-slot:title>{{ $article->title }}</x-slot:title>
        <div class="prose prose-sm dark:prose-invert max-w-none">
            {!! $article->content !!}
        </div>
        @if(!empty($article->tags))
        <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t" style="border-color: hsl(var(--color-border-light));">
            @foreach($article->tags as $tag)
            <x-rf-badge variant="secondary">{{ $tag }}</x-rf-badge>
            @endforeach
        </div>
        @endif
    </x-rf-card>

    @if(!empty($relatedArticles))
    <x-rf-card>
        <x-slot:title>مقالات ذات صلة</x-slot:title>
        <div class="space-y-3">
            @foreach($relatedArticles as $related)
            <a href="{{ route('student-services.knowledge.show', $related->id) }}" class="block p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors no-underline">
                <p class="text-sm font-medium">{{ $related->title }}</p>
                <p class="text-xs mt-1 text-text-muted">{{ Str::limit(strip_tags($related->content), 80) }}</p>
            </a>
            @endforeach
        </div>
    </x-rf-card>
    @endif

    <a href="{{ route('student-services.knowledge.index') }}" class="text-sm font-semibold text-primary hover:underline inline-flex items-center gap-1">
        &larr; العودة إلى مركز المعرفة
    </a>
</div>
@endsection
