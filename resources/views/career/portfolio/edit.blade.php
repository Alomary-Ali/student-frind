@extends('layouts.dashboard')

@section('title', 'المعرض المهني')

@section('content')
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="relative z-10">
            <x-rf-badge variant="accent" class="mb-3">المعرض المهني</x-rf-badge>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">أنشئ معرضك المهني</h1>
            <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">اعرض مشاريعك وخبراتك للعالم عبر رابط مخصص.</p>
        </div>
    </div>

    <x-rf-card>
        <x-slot:title>إعدادات المعرض</x-slot:title>
        <form method="POST" action="{{ route('career.portfolio.update') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">عنوان المعرض</label>
                    <input type="text" name="title" value="{{ $portfolio->title ?? old('title', '') }}" class="w-full rounded-xl border px-4 py-2 text-sm" required />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">الرابط المخصص</label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">rafiq.example/portfolio/</span>
                        <input type="text" name="slug" value="{{ $portfolio->slug ?? old('slug', '') }}" class="flex-1 rounded-xl border px-4 py-2 text-sm" placeholder="your-slug" />
                    </div>
                    @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">نبذة عنك</label>
                    <textarea name="bio" rows="4" class="w-full rounded-xl border px-4 py-2 text-sm">{{ $portfolio->bio ?? old('bio', '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">القالب</label>
                    <select name="theme" class="w-full rounded-xl border px-4 py-2 text-sm">
                        <option value="modern" {{ ($portfolio->theme ?? '') === 'modern' ? 'selected' : '' }}>حديث</option>
                        <option value="minimal" {{ ($portfolio->theme ?? '') === 'minimal' ? 'selected' : '' }}>بسيط</option>
                        <option value="creative" {{ ($portfolio->theme ?? '') === 'creative' ? 'selected' : '' }}>إبداعي</option>
                        <option value="professional" {{ ($portfolio->theme ?? '') === 'professional' ? 'selected' : '' }}>احترافي</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary flex-1">نشر المعرض</button>
            </div>
        </form>
    </x-rf-card>

    @if($portfolio && $portfolio->isActive)
    <x-rf-card>
        <x-slot:title>معرضك منشور 🎉</x-slot:title>
        <p class="text-sm">رابط معرضك: <a href="{{ route('career.portfolio.public', $portfolio->slug) }}" class="text-primary-500 underline" target="_blank">rafiq.example/portfolio/{{ $portfolio->slug }}</a></p>
        <p class="text-xs mt-1">عدد المشاهدات: {{ $portfolio->viewsCount }}</p>
    </x-rf-card>
    @endif
</div>
@endsection
