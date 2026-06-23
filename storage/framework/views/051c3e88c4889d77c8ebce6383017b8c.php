<?php
declare(strict_types=1);
?>



<?php $__env->startSection('title', 'مركز الفرص'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">مركز الفرص</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">اكتشف الفرص المناسبة لك وتقدم إليها</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php
                $sections = [
                    ['label' => 'الوظائف', 'count' => count($jobs ?? []), 'route' => 'opportunities.jobs', 'color' => 'primary'],
                    ['label' => 'التدريب', 'count' => count($internships ?? []), 'route' => 'opportunities.internships', 'color' => 'success'],
                    ['label' => 'المنح الدراسية', 'count' => count($scholarships ?? []), 'route' => 'opportunities.scholarships', 'color' => 'warning'],
                    ['label' => 'الدورات', 'count' => count($courses ?? []), 'route' => 'opportunities.courses', 'color' => 'info'],
                    ['label' => 'المسابقات', 'count' => count($competitions ?? []), 'route' => 'opportunities.competitions', 'color' => 'danger'],
                    ['label' => 'التطوع', 'count' => count($volunteering ?? []), 'route' => 'opportunities.index', 'color' => 'neutral'],
                    ['label' => 'المؤتمرات', 'count' => count($conferences ?? []), 'route' => 'opportunities.index', 'color' => 'info'],
                ];
            ?>

            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($section['route'])); ?>"
                   class="block p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($section['label']); ?></div>
                    <div class="mt-2 text-3xl font-bold text-primary"><?php echo e($section['count']); ?></div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">فرصة متاحة</div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if(!empty($opportunities)): ?>
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">جميع الفرص</h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate"><?php echo e($opp->title); ?></h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"><?php echo e($opp->description); ?></p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                        <span><?php echo e($opp->type); ?></span>
                                        <?php if($opp->deadline): ?>
                                            <span>آخر موعد: <?php echo e($opp->deadline); ?></span>
                                        <?php endif; ?>
                                        <?php if($opp->country): ?>
                                            <span><?php echo e($opp->country); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form method="POST" action="<?php echo e(route('opportunities.save')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="opportunity_id" value="<?php echo e($opp->id); ?>">
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
                                        <input type="hidden" name="opportunity_id" value="<?php echo e($opp->id); ?>">
                                        <input type="hidden" name="notes" value="">
                                        <?php if (isset($component)) { $__componentOriginal268f986dc315d0d5049a5ed9ae182815 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal268f986dc315d0d5049a5ed9ae182815 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-button','data' => ['type' => 'submit','variant' => 'primary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','size' => 'sm']); ?>تقدم الآن <?php echo $__env->renderComponent(); ?>
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
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/opportunities/index.blade.php ENDPATH**/ ?>