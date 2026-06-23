<?php
declare(strict_types=1);
?>



<?php $__env->startSection('title', 'طلبات التقديم'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">طلبات التقديم</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">تابع حالة طلبات التقديم التي قدمتها</p>
        </div>

        <?php if(empty($applications)): ?>
            <?php if (isset($component)) { $__componentOriginale4566c5d9a218f7fc19401c992048fcc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4566c5d9a218f7fc19401c992048fcc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-empty-state','data' => ['icon' => 'document','title' => 'لا توجد طلبات تقديم','description' => 'لم تقدم على أي فرصة بعد. استعرض الفرص المتاحة وابدأ بالتقديم','actionText' => 'استعرض الفرص','actionUrl' => route('opportunities.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'document','title' => 'لا توجد طلبات تقديم','description' => 'لم تقدم على أي فرصة بعد. استعرض الفرص المتاحة وابدأ بالتقديم','actionText' => 'استعرض الفرص','actionUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('opportunities.index'))]); ?>
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
                <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">معرف الفرصة: <?php echo e($app->opportunityId); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <?php if($app->appliedAt): ?>
                                        تاريخ التقديم: <?php echo e($app->appliedAt); ?>

                                    <?php endif; ?>
                                </p>
                                <?php if($app->notes): ?>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2"><?php echo e($app->notes); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => ''.e($app->status === 'accepted' ? 'success' : ($app->status === 'rejected' ? 'danger' : ($app->status === 'in_review' ? 'warning' : 'info'))).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($app->status === 'accepted' ? 'success' : ($app->status === 'rejected' ? 'danger' : ($app->status === 'in_review' ? 'warning' : 'info'))).'']); ?>
                                <?php echo e($app->status); ?>

                             <?php echo $__env->renderComponent(); ?>
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
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/opportunities/applications.blade.php ENDPATH**/ ?>