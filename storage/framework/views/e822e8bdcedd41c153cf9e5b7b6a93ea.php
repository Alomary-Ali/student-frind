<?php $__env->startSection('title', 'المواد الدراسية'); ?>
<?php $__env->startSection('content'); ?>


<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black" style="color:hsl(var(--color-text-primary));">المواد الدراسية</h1>
        <p class="text-sm mt-0.5" style="color:hsl(var(--color-text-secondary));">تصفح المواد المتاحة للتسجيل في الفصل الحالي</p>
    </div>
    <a href="<?php echo e(route('academic.dashboard')); ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all self-start sm:self-auto" style="border:1px solid hsl(var(--color-border));background:hsl(var(--color-surface));color:hsl(var(--color-text-secondary));">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        لوحة القيادة
    </a>
</div>


<?php if($courses->count() > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-2.5">
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="unit-card">
            
            <div class="flex items-start gap-3 mb-4">
                <div class="px-3 py-1.5 rounded-xl flex items-center justify-center font-bold text-xs bg-surface border border-border text-primary shrink-0">
                    <?php echo e($course->code); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-black leading-tight" style="color:hsl(var(--color-text-primary));"><?php echo e($course->title); ?></h3>
                    <p class="text-[11px] mt-0.5" style="color:hsl(var(--color-text-muted));"><?php echo e($course->creditHours); ?> ساعة معتمدة</p>
                </div>
            </div>

            
            <p class="text-[12.5px] leading-relaxed mb-4 line-clamp-2" style="color:hsl(var(--color-text-secondary));"><?php echo e($course->description); ?></p>

            
            <div class="flex items-center justify-between pt-3" style="border-top:1px solid hsl(var(--color-border));">
                <?php if($course->isActive): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black" style="background:hsl(var(--color-accent)/0.10);color:hsl(var(--color-accent));">نشط</span>
                <?php else: ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black" style="background:hsl(var(--color-background));color:hsl(var(--color-text-muted));">غير نشط</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="rounded-2xl p-16 text-center" style="background:hsl(var(--color-surface));border:1px solid hsl(var(--color-border));">
        <div class="w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="background:hsl(var(--color-border));">
            <svg class="h-10 w-10" style="color:hsl(var(--color-text-muted));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2" style="color:hsl(var(--color-text-primary));">لا توجد مواد متاحة</p>
        <p class="text-sm" style="color:hsl(var(--color-text-muted));">لم يتم إضافة مواد دراسية للفصل الحالي بعد</p>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/academic/courses.blade.php ENDPATH**/ ?>