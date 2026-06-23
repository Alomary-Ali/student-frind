@extends('layouts.auth')
@section('title', 'نسيت كلمة المرور')
@section('description', 'استعادة كلمة المرور لمنصة رفيق الطالب')
@section('auth-content')

<div class="flex flex-col items-center gap-3 mb-7">
    <div class="logo-ring">
        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>
    </div>
    <div class="text-center">
        <h1 class="text-2xl font-black text-text-primary tracking-tight">نسيت كلمة المرور</h1>
        <p class="text-[13px] text-text-secondary mt-1 font-medium">أدخل بريدك الإلكتروني لإرسال رابط إعادة التعيين</p>
    </div>
</div>

<form method="POST" action="{{ route('password.email') }}" class="space-y-5 animate-fade-in-up">
    @csrf

    <div class="space-y-2">
        <label for="email" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" required
               placeholder="example@university.edu.sa"
               class="rafiq-input px-4">
    </div>

    <button type="submit" class="btn btn-primary btn-full">
        <span>إرسال رابط إعادة التعيين</span>
    </button>
</form>

<p class="text-center text-sm text-text-secondary mt-6">
    <a href="{{ route('login') }}" class="font-bold text-primary hover:text-accent transition-colors">العودة لتسجيل الدخول</a>
</p>

@endsection
