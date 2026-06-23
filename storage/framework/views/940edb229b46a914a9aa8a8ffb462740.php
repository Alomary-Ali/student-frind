<?php $__env->startSection('title', 'تسجيل الدخول'); ?>
<?php $__env->startSection('description', 'بوابة الدخول لمنصة رفيق الطالب - منصة النجاح الأكاديمي والمهني'); ?>
<?php $__env->startSection('auth-content'); ?>

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

<form method="POST" action="<?php echo e(route('login.post')); ?>" class="space-y-5 animate-fade-in-up">
    <?php echo csrf_field(); ?>

    <?php if($errors->any()): ?>
        <?php if (isset($component)) { $__componentOriginal69ea3c8af4d0516f4f028a93011495c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69ea3c8af4d0516f4f028a93011495c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-alert','data' => ['variant' => 'critical','title' => 'فشل تسجيل الدخول','dismissible' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'critical','title' => 'فشل تسجيل الدخول','dismissible' => true]); ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="text-xs mt-1"><?php echo e($error); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69ea3c8af4d0516f4f028a93011495c9)): ?>
<?php $attributes = $__attributesOriginal69ea3c8af4d0516f4f028a93011495c9; ?>
<?php unset($__attributesOriginal69ea3c8af4d0516f4f028a93011495c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69ea3c8af4d0516f4f028a93011495c9)): ?>
<?php $component = $__componentOriginal69ea3c8af4d0516f4f028a93011495c9; ?>
<?php unset($__componentOriginal69ea3c8af4d0516f4f028a93011495c9); ?>
<?php endif; ?>
    <?php endif; ?>

    <div class="space-y-2">
        <label for="academic_id" class="text-[11px] font-bold text-text-secondary tracking-wider block px-1">رقم الطالب الأكاديمي</label>
        <div class="relative">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <input type="text" id="academic_id" name="academic_id"
                   value="<?php echo e(old('academic_id')); ?>"
                   placeholder="مثلاً: 20210001"
                   class="input-field pr-12 pl-4 <?php $__errorArgs = ['academic_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   required autocomplete="username">
        </div>
        <?php $__errorArgs = ['academic_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-xs text-error font-bold px-1 flex items-center gap-1"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <label for="password" class="text-[11px] font-bold text-text-secondary tracking-wider">كلمة المرور</label>
            <a href="<?php echo e(route('password.request')); ?>" class="text-[11px] font-bold text-primary hover:text-accent transition-colors">نسيت كلمة المرور؟</a>
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

    <?php if (isset($component)) { $__componentOriginal268f986dc315d0d5049a5ed9ae182815 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal268f986dc315d0d5049a5ed9ae182815 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-button','data' => ['type' => 'submit','variant' => 'primary','fullWidth' => true,'id' => 'loginBtn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','fullWidth' => true,'id' => 'loginBtn']); ?>
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        <span>دخول إلى منصتي</span>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal268f986dc315d0d5049a5ed9ae182815)): ?>
<?php $attributes = $__attributesOriginal268f986dc315d0d5049a5ed9ae182815; ?>
<?php unset($__attributesOriginal268f986dc315d0d5049a5ed9ae182815); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal268f986dc315d0d5049a5ed9ae182815)): ?>
<?php $component = $__componentOriginal268f986dc315d0d5049a5ed9ae182815; ?>
<?php unset($__componentOriginal268f986dc315d0d5049a5ed9ae182815); ?>
<?php endif; ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
    document.querySelector('form')?.addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>جاري الدخول...</span>';
        btn.disabled = true;
        btn.style.opacity = '0.8';
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/auth/login.blade.php ENDPATH**/ ?>