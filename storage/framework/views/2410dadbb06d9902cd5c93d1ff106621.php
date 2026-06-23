<?php
declare(strict_types=1);
?>



<?php $__env->startSection('title', 'الفرص الموصى بها'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">الفرص الموصى بها</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">فرص مختارة خصيصاً بناءً على ملفك الشخصي ومهاراتك</p>
        </div>

        <?php if(empty($recommendations)): ?>
            <?php if (isset($component)) { $__componentOriginale4566c5d9a218f7fc19401c992048fcc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4566c5d9a218f7fc19401c992048fcc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-empty-state','data' => ['icon' => 'star','title' => 'لا توجد توصيات بعد','description' => 'قم بإنشاء ملفك المهني وإضافة مهاراتك للحصول على توصيات مخصصة','actionText' => 'إنشاء الملف المهني','actionUrl' => route('career.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'star','title' => 'لا توجد توصيات بعد','description' => 'قم بإنشاء ملفك المهني وإضافة مهاراتك للحصول على توصيات مخصصة','actionText' => 'إنشاء الملف المهني','actionUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('career.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale4566c5d9a218f7fc19401c992048fcc)): ?>
<?php $attributes = $__attributesOriginale4566c5d9a218f7fc19401c992048fcc; ?>
<?php unset($__attributesOriginale4566c5d9a218f7fc19401c992048fcc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4566c5d9a218f7fc19401c992048fcc)): ?>
<?php $component = $__componentOriginale4566c5d9a218f7fc19401c992048fcc; ?>
<?php unset($__componentOriginale4566c5d9a218f7fc19401c992048fcc); ?>
<?php endif; ?>
        <?php else: ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white"><?php echo e($rec->opportunity->title ?? 'فرصة'); ?></h3>
                                    <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'success','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'success','size' => 'sm']); ?><?php echo e($rec->score); ?>% <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $attributes = $__attributesOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__attributesOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $component = $__componentOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__componentOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
                                </div>
                                <?php if($rec->reason): ?>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e($rec->reason); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="<?php echo e(route('opportunities.save')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="opportunity_id" value="<?php echo e($rec->opportunityId); ?>">
                                    <?php if (isset($component)) { $__componentOriginal268f986dc315d0d5049a5ed9ae182815 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal268f986dc315d0d5049a5ed9ae182815 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-button','data' => ['type' => 'submit','variant' => 'secondary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'secondary','size' => 'sm']); ?>حفظ <?php echo $__env->renderComponent(); ?>
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
                                <form method="POST" action="<?php echo e(route('opportunities.apply')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="opportunity_id" value="<?php echo e($rec->opportunityId); ?>">
                                    <?php if (isset($component)) { $__componentOriginal268f986dc315d0d5049a5ed9ae182815 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal268f986dc315d0d5049a5ed9ae182815 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-button','data' => ['type' => 'submit','variant' => 'primary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','size' => 'sm']); ?>تقدم <?php echo $__env->renderComponent(); ?>
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
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/opportunities/recommended.blade.php ENDPATH**/ ?>