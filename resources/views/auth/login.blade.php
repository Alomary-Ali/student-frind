@extends('layouts.auth')
@section('title', 'تسجيل الدخول')
@section('description', 'بوابة الدخول لمنصة رفيق الطالب - منصة النجاح الأكاديمي والمهني')
@section('auth-content')

<div class="flex flex-col items-center gap-3 mb-7">
    <div class="logo-ring">
        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
    </div>
    <div class="text-center">
        <h1 class="text-2xl font-black text-text-primary tracking-tight leading-tight">رفيق الطالب</h1>
        <p class="text-[13px] text-text-secondary mt-0.5 font-medium">منصة النجاح الأكاديمي والمهني</p>
    </div>
</div>

{{-- Journey Steps Indicator --}}
<div class="flex items-center justify-center gap-1 mb-7 animate-fade-in">
        <div class="flex flex-col items-center gap-1">
            <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
            <span class="text-[9px] font-bold text-primary uppercase tracking-wider">التخطيط</span>
        </div>
        <div class="flex-1 h-px bg-border mx-1 mb-4" style="max-width:28px;"></div>
        <div class="flex flex-col items-center gap-1">
            <div class="w-1.5 h-1.5 rounded-full bg-border"></div>
            <span class="text-[9px] font-bold text-text-muted uppercase tracking-wider">الدراسة</span>
        </div>
        <div class="flex-1 h-px bg-border mx-1 mb-4" style="max-width:28px;"></div>
        <div class="flex flex-col items-center gap-1">
            <div class="w-1.5 h-1.5 rounded-full bg-border"></div>
            <span class="text-[9px] font-bold text-text-muted uppercase tracking-wider">المهارات</span>
        </div>
        <div class="flex-1 h-px bg-border mx-1 mb-4" style="max-width:28px;"></div>
        <div class="flex flex-col items-center gap-1">
            <div class="w-1.5 h-1.5 rounded-full bg-border"></div>
            <span class="text-[9px] font-bold text-text-muted uppercase tracking-wider">الفرص</span>
        </div>
</div>

<form method="POST" action="{{ route('login.post') }}" class="space-y-5 animate-fade-in-up">
    @csrf

    @if ($errors->any())
        <x-rf-alert variant="critical" title="فشل تسجيل الدخول" dismissible>
            @foreach ($errors->all() as $error)
                <p class="text-xs mt-1">{{ $error }}</p>
            @endforeach
        </x-rf-alert>
    @endif

    <div class="space-y-2">
        <label for="academic_id" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">رقم الطالب الأكاديمي</label>
        <div class="relative">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <input type="text" id="academic_id" name="academic_id"
                   value="{{ old('academic_id') }}"
                   placeholder="مثلاً: 20210001"
                   class="input-field pr-12 pl-4 @error('academic_id') !border-error @enderror"
                   required autocomplete="username">
        </div>
        @error('academic_id')
            <p class="text-xs text-error font-bold px-1 flex items-center gap-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <label for="password" class="text-[11px] font-bold text-text-secondary tracking-wider">كلمة المرور</label>
            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-primary hover:text-accent transition-colors">نسيت كلمة المرور؟</a>
        </div>
        <div class="relative">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   class="input-field pr-12 pl-12"
                   required autocomplete="current-password">
        </div>
    </div>

    <div class="flex items-center gap-2.5">
        <input type="checkbox" id="remember" name="remember"
                class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20">
        <label for="remember" class="text-[12px] font-semibold text-text-secondary cursor-pointer">تذكرني على هذا الجهاز</label>
    </div>

    <x-rf-button type="submit" variant="primary" fullWidth id="loginBtn">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        <span>دخول إلى منصتي</span>
    </x-rf-button>
</form>

<div class="mt-5 p-4 rounded-2xl animate-fade-in card bg-primary/5 border-primary/10">
    <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-primary-light flex items-center justify-center shrink-0">
            <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-[12.5px] text-text-secondary leading-relaxed font-medium">
            يتم تزويد الطلاب ببيانات الدخول من خلال إدارة شؤون الطلاب. إذا واجهت مشكلة يرجى مراجعة الدعم الفني.
        </p>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('form')?.addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>جاري الدخول...</span>';
        btn.disabled = true;
        btn.style.opacity = '0.8';
    });
</script>
@endpush
@endsection
