<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من المستند — رفيق</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black">التحقق من المستندات</h1>
            <p class="text-sm mt-2 text-gray-600 dark:text-gray-400">أدخل رمز التحقق للتحقق من صحة المستند.</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm">
            @if(!isset($result))
            <form method="GET" action="{{ url()->current() }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">رمز التحقق</label>
                    <input type="text" name="code" required placeholder="أدخل رمز التحقق" value="{{ request('code') }}" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <button type="submit" class="btn btn-primary w-full">تحقق</button>
            </form>
            @else
            <div class="space-y-4">
                @if($result['valid'])
                <div class="p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-center">
                    <svg class="w-12 h-12 mx-auto text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-green-700 dark:text-green-300 mt-2">مستند صحيح</h2>
                    <p class="text-sm mt-1 text-green-600 dark:text-green-400">رمز التحقق صحيح والمستند موثق.</p>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b" style="border-color: hsl(var(--color-border));">
                        <span class="text-gray-500">نوع المستند</span>
                        <span class="font-medium">{{ \Modules\StudentServices\Domain\Enums\DocumentType::tryFrom($result['type'])?->label() ?? $result['type'] }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b" style="border-color: hsl(var(--color-border));">
                        <span class="text-gray-500">اسم الطالب</span>
                        <span class="font-medium">{{ $result['student_name'] }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b" style="border-color: hsl(var(--color-border));">
                        <span class="text-gray-500">تاريخ الإصدار</span>
                        <span class="font-medium">{{ $result['generated_at'] }}</span>
                    </div>
                </div>
                @else
                <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-center">
                    <svg class="w-12 h-12 mx-auto text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-red-700 dark:text-red-300 mt-2">مستند غير صحيح</h2>
                    <p class="text-sm mt-1 text-red-600 dark:text-red-400">رمز التحقق غير صحيح أو المستند غير موثق.</p>
                </div>
                @endif

                <a href="{{ url()->current() }}" class="btn btn-ghost w-full text-sm">تحقق من مستند آخر</a>
            </div>
            @endif
        </div>

        <p class="text-center text-xs text-gray-400 mt-8">خدمة التحقق من المستندات — رفيق</p>
    </div>
</body>
</html>
