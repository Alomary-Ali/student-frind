<?php $__env->startSection('title', 'غير مصرح'); ?>
<?php $__env->startSection('description', 'ليس لديك صلاحية للوصول إلى هذه الصفحة'); ?>
<?php $__env->startSection('auth-content'); ?>

<div class="flex flex-col items-center gap-3 mb-7">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto" style="background:hsl(var(--color-error)/0.10);">
        <svg class="h-10 w-10" style="color:hsl(var(--color-error));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    <div class="text-center">
        <h1 class="text-2xl font-black text-text-primary tracking-tight">غير مصرح</h1>
        <p class="text-[13px] text-text-secondary mt-1 font-medium">ليس لديك صلاحية للوصول إلى هذه الصفحة</p>
    </div>
</div>

<div class="text-center animate-fade-in-up">
    <a href="<?php echo e(route('login')); ?>"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all shadow-lg" style="background:hsl(var(--color-primary));">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        العودة للصفحة الرئيسية
    </a>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/auth/unauthorized.blade.php ENDPATH**/ ?>