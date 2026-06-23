<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portfolio['portfolio']->title }} — المعرض المهني</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-950 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 py-12">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-black">{{ $portfolio['portfolio']->title }}</h1>
            @if($portfolio['portfolio']->bio)
            <p class="text-lg mt-4 text-gray-600 dark:text-gray-400">{{ $portfolio['portfolio']->bio }}</p>
            @endif
        </div>

        {{-- Profile Info --}}
        @if($portfolio['profile'])
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 mb-8 shadow-sm">
            <h2 class="text-xl font-bold mb-4">الملف الشخصي</h2>
            <p class="text-sm">التخصص: {{ $portfolio['profile']['major'] ?? '—' }}</p>
        </div>
        @endif

        {{-- Portfolio Items --}}
        @if(!empty($portfolio['portfolio_items']))
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 mb-8 shadow-sm">
            <h2 class="text-xl font-bold mb-4">المشاريع</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($portfolio['portfolio_items'] as $item)
                <div class="p-4 rounded-xl border">
                    <h3 class="font-medium">{{ $item['title'] }}</h3>
                    @if(!empty($item['technologies']))
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($item['technologies'] as $tech)
                        <span class="text-xs px-2 py-1 rounded-full bg-primary-100 text-primary-700">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif
                    @if($item['project_url'])
                    <a href="{{ $item['project_url'] }}" target="_blank" class="text-xs text-primary-500 mt-2 block">عرض المشروع ←</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Experiences --}}
        @if(!empty($portfolio['experiences']))
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold mb-4">الخبرات</h2>
            <div class="space-y-4">
                @foreach($portfolio['experiences'] as $exp)
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium">{{ $exp['position'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $exp['company'] }}</p>
                    </div>
                    @if($exp['is_current'])
                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">حالياً</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <p class="text-center text-xs text-gray-400 mt-12">أنشئ معرضك المهني على رفيق</p>
    </div>
</body>
</html>
