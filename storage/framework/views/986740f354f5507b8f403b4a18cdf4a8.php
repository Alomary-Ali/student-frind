<?php $__env->startSection('title', 'الأهداف'); ?>
<?php $__env->startSection('content'); ?>


<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-black">الأهداف</h1>
        <p class="text-sm mt-0.5 text-text-muted">إدارة أهدافك الشخصية والأكاديمية</p>
    </div>
    <a href="<?php echo e(route('productivity.goals.create')); ?>"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold transition-all shadow-md self-start sm:self-auto bg-primary">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        إنشاء هدف جديد
    </a>
</div>


<?php if(count($goals) > 0): ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="unit-card p-5 animate-fade-in-up" style="animation-delay:<?php echo e(($i % 8) * 50); ?>ms">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="text-sm font-black"><?php echo e($goal->title); ?></h3>
                        <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => ''.e($goal->priority === 'urgent' ? 'error' : ($goal->priority === 'high' ? 'primary' : 'muted')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($goal->priority === 'urgent' ? 'error' : ($goal->priority === 'high' ? 'primary' : 'muted')).'']); ?>
                            <?php if($goal->priority === 'urgent'): ?> عاجل
                            <?php elseif($goal->priority === 'high'): ?> مرتفع
                            <?php elseif($goal->priority === 'medium'): ?> متوسط
                            <?php else: ?> عادي <?php endif; ?>
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
                        <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => ''.e($goal->status === 'completed' ? 'accent' : ($goal->status === 'active' ? 'primary' : 'muted')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($goal->status === 'completed' ? 'accent' : ($goal->status === 'active' ? 'primary' : 'muted')).'']); ?>
                            <?php if($goal->status === 'completed'): ?> مكتمل
                            <?php elseif($goal->status === 'active'): ?> نشط
                            <?php else: ?> معلق <?php endif; ?>
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
                        <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'muted']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'muted']); ?>
                            <?php if($goal->goalType === 'academic'): ?> أكاديمي
                            <?php elseif($goal->goalType === 'personal'): ?> شخصي
                            <?php elseif($goal->goalType === 'career'): ?> مهني
                            <?php else: ?> <?php echo e($goal->goalType); ?> <?php endif; ?>
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

                    
                    <p class="text-[12.5px] mb-4 leading-relaxed text-text-muted"><?php echo e($goal->description); ?></p>

                    
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex justify-between text-[11px] font-bold mb-1.5">
                                <span class="text-text-muted">التقدم</span>
                                <span class="text-primary"><?php echo e(number_format($goal->progress, 1)); ?>%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill
                                    <?php if($goal->status === 'completed'): ?> progress-fill-accent
                                    <?php else: ?> progress-fill-primary <?php endif; ?>"
                                    style="width: <?php echo e($goal->progress); ?>%"></div>
                            </div>
                        </div>
                        <?php if($goal->targetDate): ?>
                        <div class="text-right shrink-0">
                            <p class="text-[10px] font-medium text-text-muted">الموعد النهائي</p>
                            <p class="text-[11px] font-bold text-text-primary"><?php echo e($goal->targetDate); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <a href="<?php echo e(route('productivity.goals.show', $goal->id)); ?>"
                   class="shrink-0 inline-flex items-center gap-1.5 text-[12px] font-bold hover:text-accent transition-colors mt-1 text-primary">
                    التفاصيل
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="p-16 rounded-xl border border-border bg-surface shadow-sm text-center animate-scale-in">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 bg-primary/8">
            <svg class="h-10 w-10 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-base font-black mb-2 text-text-primary">لا توجد أهداف بعد</p>
        <p class="text-sm mb-6 text-text-muted">حدد هدفك الأول وابدأ رحلة النجاح</p>
        <a href="<?php echo e(route('productivity.goals.create')); ?>"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all shadow-lg bg-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            إنشاء هدف جديد
        </a>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/productivity/goals.blade.php ENDPATH**/ ?>