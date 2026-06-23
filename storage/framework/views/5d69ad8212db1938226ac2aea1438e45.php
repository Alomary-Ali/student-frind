<?php $__env->startSection('title', 'الملف المهني'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8" style="background: var(--gradient-navy); box-shadow: var(--shadow-navy);">
        <div class="absolute inset-0 orb orb-primary opacity-35 -bottom-20 -right-20"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'accent','class' => 'mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'accent','class' => 'mb-3']); ?>التطوير المهني <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $attributes = $__attributesOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__attributesOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $component = $__componentOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__componentOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">بوابة التطوير المهني والملف الشخصي</h1>
                <p class="text-sm md:text-base mt-2" style="color: hsl(var(--color-surface) / 0.7);">ابنِ هويتك المهنية، أدر مشاريعك، تتبع أهدافك المهنية، وولد سيرتك الذاتية بنقرة زر.</p>
            </div>
            <button onclick="openModal('edit-profile-modal')" class="btn btn-primary shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                تحديث البيانات الأساسية
            </button>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        
        <div class="lg:col-span-1 space-y-8">
            
            <div class="card card-elevated p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gradient-primary">الملف التعريفي</h2>
                    <div class="h-1 w-12 bg-primary rounded-full mt-1.5"></div>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] section-label">التخصص / المجال المستهدف</span>
                        <p class="text-base font-bold mt-1 text-text-primary"><?php echo e($profile->major); ?></p>
                    </div>

                    <div>
                        <span class="text-[10px] section-label">الملخص المهني</span>
                        <p class="text-sm mt-1 leading-relaxed text-text-secondary">
                            <?php echo e($profile->summary ?: 'لا يوجد ملخص مهني حالياً. اضغط على تحديث البيانات الأساسية لكتابة ملخص يعبر عن شغفك ومهاراتك.'); ?>

                        </p>
                    </div>

                    <div>
                        <span class="text-[10px] section-label">الاهتمامات المهنية</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <?php $__empty_1 = true; $__currentLoopData = $profile->interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?><?php echo e($interest); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $attributes = $__attributesOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__attributesOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $component = $__componentOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__componentOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-xs text-text-muted">لم يتم تحديد اهتمامات</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] section-label">اللغات</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <?php $__empty_1 = true; $__currentLoopData = $profile->languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'navy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'navy']); ?><?php echo e($lang); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $attributes = $__attributesOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__attributesOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $component = $__componentOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__componentOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-xs text-text-muted">لم يتم تحديد لغات</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card card-elevated p-6 space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gradient-primary">الأهداف المهنية</h2>
                        <div class="h-1 w-12 bg-primary rounded-full mt-1.5"></div>
                    </div>
                    <button onclick="openModal('add-goal-modal')" class="btn btn-sm btn-ghost">
                        + هدف جديد
                    </button>
                </div>

                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $profile->careerGoals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-3.5 rounded-xl border space-y-3" style="border-color: hsl(var(--color-border-light)); background: hsl(var(--color-background) / 0.5);">
                            <div class="flex justify-between items-start gap-3">
                                <h3 class="text-sm font-bold truncate text-text-primary"><?php echo e($goal->title); ?></h3>
                                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => ''.e($goal->status === 'completed' ? 'accent' : 'warning').'','class' => 'shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($goal->status === 'completed' ? 'accent' : 'warning').'','class' => 'shrink-0']); ?>
                                    <?php echo e($goal->status === 'completed' ? 'مكتمل' : 'نشط'); ?>

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
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-[10px] text-text-secondary">
                                    <span>التقدم: <?php echo e($goal->progress); ?>%</span>
                                    <span>المستهدف: <?php echo e($goal->targetDate); ?></span>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill <?php echo e($goal->status === 'completed' ? 'progress-fill-accent' : 'progress-fill-primary'); ?>" style="width: <?php echo e($goal->progress); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-6 text-sm text-text-muted">
                            لا توجد أهداف مهنية نشطة حالياً.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-8">
            
            
            <div class="flex border-b" style="border-color: hsl(var(--color-border));">
                <button onclick="switchTab('portfolio')" id="tab-btn-portfolio" class="tab-btn active">معرض المشاريع (Portfolio)</button>
                <button onclick="switchTab('experience')" id="tab-btn-experience" class="tab-btn">الخبرات العملية والأنشطة</button>
                <button onclick="switchTab('resume')" id="tab-btn-resume" class="tab-btn">السيرة الذاتية (CV)</button>
            </div>

            
            <div id="tab-content-portfolio" class="tab-content space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gradient-primary">مشاريعك البرمجية والعملية</h2>
                    <button onclick="openModal('add-portfolio-modal')" class="btn btn-primary btn-sm">
                        + إضافة مشروع
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php $__empty_1 = true; $__currentLoopData = $profile->portfolioItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="card p-5 hover-lift flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h3 class="text-base font-bold text-text-primary"><?php echo e($item->title); ?></h3>
                                <p class="text-xs text-text-muted"><?php echo e($item->startDate); ?> إلى <?php echo e($item->endDate ?: 'الآن'); ?></p>
                                <p class="text-sm line-clamp-3 leading-relaxed text-text-secondary"><?php echo e($item->description); ?></p>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex flex-wrap gap-1.5">
                                    <?php $__currentLoopData = $item->technologies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'navy','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'navy','size' => 'sm']); ?><?php echo e($tech); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $attributes = $__attributesOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__attributesOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27465f7ae487588dd0301796cfc6a836)): ?>
<?php $component = $__componentOriginal27465f7ae487588dd0301796cfc6a836; ?>
<?php unset($__componentOriginal27465f7ae487588dd0301796cfc6a836); ?>
<?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="flex gap-4 border-t pt-3" style="border-color: hsl(var(--color-border-light));">
                                    <?php if($item->projectUrl): ?>
                                        <a href="<?php echo e($item->projectUrl); ?>" target="_blank" class="text-xs font-semibold flex items-center gap-1 hover:underline text-primary">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            الرابط
                                        </a>
                                    <?php endif; ?>
                                    <?php if($item->githubUrl): ?>
                                        <a href="<?php echo e($item->githubUrl); ?>" target="_blank" class="text-xs font-semibold flex items-center gap-1 hover:underline text-text-primary">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.577.688.479C19.138 20.162 22 16.418 22 12c0-5.523-4.478-10-10-10z" clip-rule="evenodd" />
                                            </svg>
                                            GitHub
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-2 text-center py-12 border border-dashed rounded-3xl space-y-4" style="border-color: hsl(var(--color-border));">
                            <svg class="mx-auto w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:hsl(var(--color-text-muted));"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <p style="color:hsl(var(--color-text-muted));">لم تقم بإضافة أي مشاريع حتى الآن.</p>
                            <button onclick="openModal('add-portfolio-modal')" class="btn btn-primary btn-sm mt-4">
                                أضف مشروعك الأول
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div id="tab-content-experience" class="tab-content hidden space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gradient-primary">الخبرات والمسيرة المهنية</h2>
                    <button onclick="openModal('add-experience-modal')" class="btn btn-primary btn-sm">
                        + إضافة خبرة
                    </button>
                </div>

                <div class="relative border-r-2 pr-6 space-y-6" style="border-color: hsl(var(--color-border));">
                    <?php $__empty_1 = true; $__currentLoopData = $profile->experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="relative">
                            
                            <div class="absolute right-[-31px] top-1.5 w-4 h-4 rounded-full border-4 bg-surface" style="border-color: hsl(var(--color-primary));"></div>
                            
                            <div class="card p-5 space-y-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-base font-bold text-text-primary"><?php echo e($exp->position); ?></h3>
                                        <p class="text-sm font-semibold text-primary"><?php echo e($exp->company); ?></p>
                                    </div>
                                    <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => ''.e($exp->isCurrent ? 'accent' : 'navy').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => ''.e($exp->isCurrent ? 'accent' : 'navy').'']); ?>
                                        <?php echo e($exp->isCurrent ? 'حالي' : 'منتهي'); ?>

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
                                <p class="text-xs text-text-muted"><?php echo e($exp->startDate); ?> إلى <?php echo e($exp->endDate ?: 'الآن'); ?></p>
                                <p class="text-sm leading-relaxed mt-2 text-text-secondary"><?php echo e($exp->description); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12 border border-dashed rounded-3xl space-y-4" style="border-color: hsl(var(--color-border));">
                            <svg class="mx-auto w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:hsl(var(--color-text-muted));"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <p style="color:hsl(var(--color-text-muted));">لم تقم بإضافة أي خبرات سابقة.</p>
                            <button onclick="openModal('add-experience-modal')" class="btn btn-primary btn-sm mt-4">
                                أضف خبرتك الأولى
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div id="tab-content-resume" class="tab-content hidden space-y-6">
                <div class="card card-elevated p-6 space-y-6">
                    <h2 class="text-lg font-bold text-gradient-primary">مولد السيرة الذاتية الذكي</h2>
                    <p class="text-sm leading-relaxed text-text-secondary">
                        يقوم رفيق بتجميع بياناتك الأساسية، ومشاريعك، وخبراتك العملية، ومهاراتك الحالية، وتنسيقها تلقائياً في سيرة ذاتية احترافية جاهزة للاستخدام.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <div class="p-5 border rounded-2xl flex flex-col justify-between items-start gap-4" style="border-color: hsl(var(--color-border));">
                            <div>
                                <h3 class="font-bold text-text-primary">القالب الحديث (Modern)</h3>
                                <p class="text-xs mt-1 text-text-secondary">قالب عصري مناسب للتخصصات التقنية والهندسية بتصميم جذاب.</p>
                            </div>
                            <button class="btn btn-sm btn-primary">توليد ومعاينة</button>
                        </div>

                        <div class="p-5 border rounded-2xl flex flex-col justify-between items-start gap-4" style="border-color: hsl(var(--color-border));">
                            <div>
                                <h3 class="font-bold text-text-primary">القالب الأكاديمي (Academic)</h3>
                                <p class="text-xs mt-1 text-text-secondary">تصميم كلاسيكي رصين مناسب للتقديم الأكاديمي والمنح البحثية.</p>
                            </div>
                            <button class="btn btn-sm btn-secondary">توليد ومعاينة</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div id="edit-profile-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 p-4">
    <div class="card p-6 max-w-lg w-full space-y-6 animate-scale-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-primary">تحديث البيانات الأساسية</h3>
            <button onclick="closeModal('edit-profile-modal')" class="text-xl font-bold text-text-muted">&times;</button>
        </div>
        <form action="<?php echo e(route('career.update')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">التخصص أو المجال المهني المستهدف</label>
                <input type="text" name="major" value="<?php echo e($profile->major); ?>" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">الملخص المهني</label>
                <textarea name="summary" rows="4" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));"><?php echo e($profile->summary); ?></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('edit-profile-modal')" class="btn btn-sm btn-ghost">إلغاء</button>
                <button type="submit" class="btn btn-sm btn-primary">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>


<div id="add-goal-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 p-4">
    <div class="card p-6 max-w-md w-full space-y-6 animate-scale-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-primary">إضافة هدف مهني</h3>
            <button onclick="closeModal('add-goal-modal')" class="text-xl font-bold text-text-muted">&times;</button>
        </div>
        <form action="<?php echo e(route('career.goals.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">عنوان الهدف</label>
                <input type="text" name="title" required placeholder="مثال: الحصول على شهادة AWS Cloud Practitioner" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ الإنجاز المستهدف</label>
                <input type="date" name="target_date" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('add-goal-modal')" class="btn btn-sm btn-ghost">إلغاء</button>
                <button type="submit" class="btn btn-sm btn-primary">إضافة الهدف</button>
            </div>
        </form>
    </div>
</div>


<div id="add-portfolio-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 p-4">
    <div class="card p-6 max-w-lg w-full space-y-6 animate-scale-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-primary">إضافة مشروع جديد للمعرض</h3>
            <button onclick="closeModal('add-portfolio-modal')" class="text-xl font-bold text-text-muted">&times;</button>
        </div>
        <form action="<?php echo e(route('career.portfolio.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">عنوان المشروع</label>
                <input type="text" name="title" required placeholder="مثال: منصة رفيق التعليمية" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">وصف المشروع</label>
                <textarea name="description" required rows="3" placeholder="اكتب نبذة مختصرة عن فكرة المشروع والتقنيات المستخدمة ودورك فيه" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">التقنيات المستخدمة</label>
                    <input type="text" name="technologies" placeholder="مثال: Laravel, Vue.js, MySQL (افصل بينها بفاصلة)" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">رابط المشروع المعروض</label>
                    <input type="url" name="project_url" placeholder="https://" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">رابط مستودع الكود (GitHub)</label>
                    <input type="url" name="github_url" placeholder="https://github.com/..." class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ البدء</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ الانتهاء (اختياري)</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('add-portfolio-modal')" class="btn btn-sm btn-ghost">إلغاء</button>
                <button type="submit" class="btn btn-sm btn-primary">إضافة المشروع</button>
            </div>
        </form>
    </div>
</div>


<div id="add-experience-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 p-4">
    <div class="card p-6 max-w-lg w-full space-y-6 animate-scale-in">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-text-primary">إضافة خبرة عملية أو نشاط</h3>
            <button onclick="closeModal('add-experience-modal')" class="text-xl font-bold text-text-muted">&times;</button>
        </div>
        <form action="<?php echo e(route('career.experience.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">الشركة / المنظمة</label>
                    <input type="text" name="company" required placeholder="مثال: شركة رفيق المحدودة" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">المسمى الوظيفي / الدور</label>
                    <input type="text" name="position" required placeholder="مثال: متدرب تطوير واجهات" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-text-secondary">وصف المهام والإنجازات</label>
                <textarea name="description" required rows="3" placeholder="صف دورك وأبرز المسؤوليات والتقنيات التي تعاملت معها" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ البدء</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-2 text-text-secondary">تاريخ الانتهاء</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text-primary" style="border-color: hsl(var(--color-border));">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_current" value="1" id="is_current" class="rounded" style="border-color: hsl(var(--color-border));">
                <label for="is_current" class="text-xs font-bold text-text-secondary">ما زلت أعمل هنا حالياً</label>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('add-experience-modal')" class="btn btn-sm btn-ghost">إلغاء</button>
                <button type="submit" class="btn btn-sm btn-primary">إضافة الخبرة</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById(`tab-btn-${tabName}`).classList.add('active');
        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/career/index.blade.php ENDPATH**/ ?>