<?php $__env->startSection('title', 'إنشاء حساب جديد'); ?>
<?php $__env->startSection('description', 'إنشاء حساب جديد على منصة رفيق الطالب'); ?>
<?php $__env->startSection('auth-content'); ?>

<div class="flex flex-col items-center gap-3 mb-7">
    <div class="logo-ring">
        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
    </div>
    <div class="text-center">
        <h1 class="text-2xl font-black text-text-primary tracking-tight">إنشاء حساب جديد</h1>
        <p class="text-[13px] text-text-secondary mt-1 font-medium">منصة النجاح الأكاديمي والمهني</p>
    </div>
</div>

<form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-5 animate-fade-in-up">
    <?php echo csrf_field(); ?>

    <div class="space-y-2">
        <label for="name" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">الاسم الكامل</label>
        <input type="text" id="name" name="name" required
               placeholder="الاسم الكامل"
               class="rafiq-input px-4">
    </div>

    <div class="space-y-2">
        <label for="email" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" required
               placeholder="example@university.edu.sa"
               class="rafiq-input px-4">
    </div>

    <div class="space-y-2">
        <label for="password" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">كلمة المرور</label>
        <input type="password" id="password" name="password" required
               placeholder="••••••••"
               class="rafiq-input px-4">
    </div>

    <div class="space-y-2">
        <label for="password_confirmation" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">تأكيد كلمة المرور</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required
               placeholder="••••••••"
               class="rafiq-input px-4">
    </div>

    <button type="submit" class="btn btn-primary btn-full">
        <span>إنشاء حساب</span>
    </button>
</form>

<p class="text-center text-sm text-text-secondary mt-6">
    لديك حساب بالفعل؟
    <a href="<?php echo e(route('login')); ?>" class="font-bold text-primary hover:text-accent transition-colors">تسجيل الدخول</a>
</p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/auth/register.blade.php ENDPATH**/ ?>