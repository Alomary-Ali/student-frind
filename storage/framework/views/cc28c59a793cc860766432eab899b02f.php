<?php $__env->startSection('title', 'لوحة القيادة الأكاديمية'); ?>
<?php $__env->startSection('content'); ?>

<?php if($error): ?>
    <?php if (isset($component)) { $__componentOriginal69ea3c8af4d0516f4f028a93011495c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69ea3c8af4d0516f4f028a93011495c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-alert','data' => ['variant' => 'critical','title' => 'خطأ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'critical','title' => 'خطأ']); ?>
        <?php echo e($error); ?>

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
<?php elseif(!$profile): ?>
    <?php if (isset($component)) { $__componentOriginale4566c5d9a218f7fc19401c992048fcc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4566c5d9a218f7fc19401c992048fcc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-empty-state','data' => ['title' => 'لا يوجد ملف أكاديمي','description' => 'يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'لا يوجد ملف أكاديمي','description' => 'يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي']); ?>
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

<div class="card-elevated p-4 mb-6" data-observe-section="academic-dashboard">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-6">
            <div>
                <p class="section-label">الفصل الحالي</p>
                <p class="text-sm font-black mt-0.5 text-text-primary"><?php echo e($profile['current_semester'] ?? 'غير محدد'); ?></p>
            </div>
            <div class="h-8 w-px bg-border"></div>
            <div>
                <p class="section-label">الحالة الأكاديمية</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="w-2 h-2 rounded-full bg-accent"></div>
                    <p class="text-sm font-black text-accent"><?php echo e($profile['academic_standing'] ?? 'ممتاز'); ?></p>
                </div>
            </div>
            <div class="h-8 w-px bg-border"></div>
            <div>
                <p class="section-label">نسبة التقدم</p>
                <p class="text-sm font-black mt-0.5 text-text-primary"><?php echo e($graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) . '% مكتمل' : '0% مكتمل'); ?></p>
            </div>
        </div>
        <?php if(count($alerts ?? []) > 0): ?>
        <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'error','dot' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'error','dot' => true]); ?>
            تنبيهات: <?php echo e(count($alerts)); ?>

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
        <?php endif; ?>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-2.5 mb-6">

    
    <div class="space-y-2.5" data-observe-section="academic-dashboard">

        
        <div class="unit-card">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="section-label">المعدل التراكمي</p>
                    <p class="text-[2.25rem] font-black leading-none mt-2 text-text-primary"><?php echo e(number_format($profile['cumulative_gpa'] ?? 0, 2)); ?></p>
                    <p class="text-xs mt-1 text-text-secondary">من 4.00 نقطة</p>
                </div>
                <div class="unit-icon-box">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                </div>
            </div>
            <?php if (isset($component)) { $__componentOriginal910788ea410fffefedcbd7bf359e6239 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal910788ea410fffefedcbd7bf359e6239 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-progress','data' => ['value' => min(100, ($profile['cumulative_gpa'] ?? 0) / 4 * 100),'variant' => 'accent','size' => 'sm','showPercentage' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(min(100, ($profile['cumulative_gpa'] ?? 0) / 4 * 100)),'variant' => 'accent','size' => 'sm','showPercentage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal910788ea410fffefedcbd7bf359e6239)): ?>
<?php $attributes = $__attributesOriginal910788ea410fffefedcbd7bf359e6239; ?>
<?php unset($__attributesOriginal910788ea410fffefedcbd7bf359e6239); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal910788ea410fffefedcbd7bf359e6239)): ?>
<?php $component = $__componentOriginal910788ea410fffefedcbd7bf359e6239; ?>
<?php unset($__componentOriginal910788ea410fffefedcbd7bf359e6239); ?>
<?php endif; ?>
            <p class="text-[10px] font-black mt-1.5 text-accent"><?php echo e(min(100, round(($profile['cumulative_gpa'] ?? 0) / 4 * 100))); ?>% من الحد الأقصى</p>
        </div>

        
        <div class="unit-card">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="section-label">الساعات المعتمدة</p>
                    <div class="flex items-baseline gap-1.5 mt-2">
                        <span class="text-[2.25rem] font-black leading-none text-text-primary"><?php echo e($graduationProgress ? $graduationProgress['credits_earned'] : 0); ?></span>
                        <span class="text-base font-semibold text-text-muted">/ <?php echo e($graduationProgress ? $graduationProgress['credits_required'] : 0); ?></span>
                    </div>
                    <p class="text-xs mt-1 text-text-secondary">ساعة معتمدة</p>
                </div>
                <div class="unit-icon-box">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <?php if (isset($component)) { $__componentOriginal910788ea410fffefedcbd7bf359e6239 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal910788ea410fffefedcbd7bf359e6239 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-progress','data' => ['value' => $graduationProgress ? $graduationProgress['completion_percentage'] : 0,'variant' => 'primary','size' => 'sm','showPercentage' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($graduationProgress ? $graduationProgress['completion_percentage'] : 0),'variant' => 'primary','size' => 'sm','showPercentage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal910788ea410fffefedcbd7bf359e6239)): ?>
<?php $attributes = $__attributesOriginal910788ea410fffefedcbd7bf359e6239; ?>
<?php unset($__attributesOriginal910788ea410fffefedcbd7bf359e6239); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal910788ea410fffefedcbd7bf359e6239)): ?>
<?php $component = $__componentOriginal910788ea410fffefedcbd7bf359e6239; ?>
<?php unset($__componentOriginal910788ea410fffefedcbd7bf359e6239); ?>
<?php endif; ?>
            <p class="text-[10px] font-black mt-1.5 text-primary"><?php echo e($graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) : 0); ?>% من متطلبات التخرج</p>
        </div>

        
        <div class="unit-card">
            <div class="flex items-center justify-between mb-3">
                <p class="section-label">الوضع الأكاديمي</p>
                <div class="unit-icon-box">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-lg font-black text-text-primary"><?php echo e($profile['academic_status'] ?? 'مستقر'); ?></p>
            <p class="text-xs mt-0.5 text-text-secondary">المستوى <?php echo e($profile['level'] ?? '1'); ?></p>
        </div>
    </div>

    
    <div class="space-y-2.5" data-observe-section="academic-dashboard">

        
        <div class="unit-card">
            <p class="section-label mb-4">إجراءات سريعة</p>
            <div class="grid grid-cols-2 gap-2.5">
                <?php $__currentLoopData = [
                    ['route' => 'academic.plan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'الخطة الدراسية'],
                    ['route' => 'productivity.tasks.create', 'icon' => 'M12 4v16m8-8H4', 'label' => 'إضافة مهمة'],
                    ['route' => 'productivity.calendar', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'التقويم'],
                    ['route' => '#', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 8 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'label' => 'المساعد الذكي'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($action['route'] === '#' ? '#' : route($action['route'])); ?>"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl hover:shadow-md active:scale-95 transition-all group bg-background">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-surface border border-border">
                        <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($action['icon']); ?>"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold text-center leading-tight text-text-secondary"><?php echo e($action['label']); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($alerts && count($alerts) > 0): ?>
        <div class="unit-card">
            <div class="flex items-center justify-between mb-4">
                <p class="section-label">التنبيهات</p>
                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'error']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'error']); ?><?php echo e(count($alerts)); ?> <?php echo $__env->renderComponent(); ?>
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
            <div class="space-y-2.5">
                <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-3 rounded-xl border-r-[3px] border-accent bg-accent/5 <?php echo e($alert->severity === 'critical' ? 'alert-critical' : ($alert->severity === 'high' ? 'alert-high' : 'alert-normal')); ?>">
                    <div class="flex items-center gap-2 mb-1.5">
                        <svg class="h-4 w-4 <?php echo e($alert->severity === 'critical' ? 'text-critical' : ($alert->severity === 'high' ? 'text-high' : 'text-normal')); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-xs font-bold <?php echo e($alert->severity === 'critical' ? 'text-critical' : ($alert->severity === 'high' ? 'text-high' : 'text-normal')); ?>">
                            <?php echo e($alert->alertType === 'low_gpa' ? 'تحذير GPA' : ($alert->alertType === 'credit_deficit' ? 'نقص ساعات' : ($alert->alertType === 'graduation_delay' ? 'تأخر تخرج' : 'تنبيه'))); ?>

                        </span>
                    </div>
                    <p class="text-sm font-bold text-text-primary"><?php echo e($alert->message); ?></p>
                    <p class="text-xs mt-0.5 text-text-muted"><?php echo e(date('d M Y', strtotime($alert->createdAt))); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="ai-card rounded-2xl p-5 relative overflow-hidden">
            <div class="ai-scan-line"></div>
            <div class="ai-glow" style="top:-30px;right:-30px;animation:float 6s ease-in-out infinite;"></div>
            <div class="ai-glow" style="bottom:-30px;left:-30px;animation:float 9s ease-in-out infinite reverse;"></div>
            <div class="absolute inset-0 bg-gradient-to-l from-accent/10 to-transparent opacity-50 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2.5 mb-5">
                    <div class="glass-container w-8 h-8 rounded-xl flex items-center justify-center">
                        <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-black text-sm leading-tight">رؤية الذكاء الاصطناعي</p>
                        <p class="text-white/40 text-[10px] font-medium">تحليل مباشر</p>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="rounded-xl p-3 bg-white/10 border border-white/10">
                        <p class="text-white/50 text-[10px] font-semibold uppercase tracking-wider mb-1">تاريخ التخرج</p>
                        <p class="text-white font-black text-sm"><?php echo e($graduationProgress && $graduationProgress['estimated_graduation_date'] ? date('F Y', strtotime($graduationProgress['estimated_graduation_date'])) : 'غير محدد'); ?></p>
                    </div>
                    <div class="rounded-xl p-3 bg-white/10 border border-white/10">
                        <p class="text-white/50 text-[10px] font-semibold uppercase tracking-wider mb-1">تحليل المخاطر</p>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-accent"></div>
                            <p class="font-black text-sm text-accent">مخاطر منخفضة</p>
                        </div>
                    </div>
                    <div class="rounded-xl p-3 bg-white/10 border border-white/10">
                        <p class="text-white/50 text-[10px] font-semibold uppercase tracking-wider mb-1">التوصية</p>
                        <p class="text-white/80 text-[12.5px] leading-relaxed">أداء ممتاز، استمر في المسار الحالي</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="space-y-2.5" data-observe-section="academic-dashboard">

        
        <div class="unit-card">
            <div class="flex items-center justify-between mb-4">
                <p class="section-label">يحتاج انتباهك</p>
                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'error']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'error']); ?><?php echo e(count($alerts ?? [])); ?> عناصر <?php echo $__env->renderComponent(); ?>
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
            <?php if($alerts && count($alerts) > 0): ?>
                <div class="grid grid-cols-1 gap-3">
                    <?php $__currentLoopData = array_slice($alerts, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 rounded-xl border-r-[3px] <?php echo e($alert->severity === 'critical' ? 'alert-critical' : ($alert->severity === 'high' ? 'alert-high' : 'alert-normal')); ?>">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4 <?php echo e($alert->severity === 'critical' ? 'text-critical' : ($alert->severity === 'high' ? 'text-high' : 'text-normal')); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span class="text-xs font-bold <?php echo e($alert->severity === 'critical' ? 'text-critical' : ($alert->severity === 'high' ? 'text-high' : 'text-normal')); ?>">
                                    <?php echo e($alert->alertType === 'low_gpa' ? 'تحذير GPA' : ($alert->alertType === 'credit_deficit' ? 'نقص ساعات' : ($alert->alertType === 'graduation_delay' ? 'تأخر تخرج' : 'تنبيه'))); ?>

                                </span>
                            </div>
                            <p class="text-sm font-bold text-text-primary"><?php echo e($alert->message); ?></p>
                            <p class="text-xs mt-0.5 text-text-muted"><?php echo e(date('d M Y', strtotime($alert->createdAt))); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="p-8 text-center">
                    <svg class="h-12 w-12 mx-auto mb-3 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-text-primary">لا توجد تنبيهات</p>
                    <p class="text-xs mt-1 text-text-muted">أنت على المسار الصحيح</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="unit-card">
            <div class="flex items-center justify-between mb-6">
                <p class="section-label">المسار الأكاديمي</p>
                <?php if (isset($component)) { $__componentOriginal27465f7ae487588dd0301796cfc6a836 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27465f7ae487588dd0301796cfc6a836 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-badge','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?><?php echo e($graduationProgress ? number_format($graduationProgress['completion_percentage'], 0) . '% مكتمل' : '0% مكتمل'); ?> <?php echo $__env->renderComponent(); ?>
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

            <?php if (isset($component)) { $__componentOriginal910788ea410fffefedcbd7bf359e6239 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal910788ea410fffefedcbd7bf359e6239 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rf-progress','data' => ['value' => $graduationProgress ? $graduationProgress['completion_percentage'] : 0,'variant' => 'gradient','size' => 'md','showPercentage' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rf-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($graduationProgress ? $graduationProgress['completion_percentage'] : 0),'variant' => 'gradient','size' => 'md','showPercentage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal910788ea410fffefedcbd7bf359e6239)): ?>
<?php $attributes = $__attributesOriginal910788ea410fffefedcbd7bf359e6239; ?>
<?php unset($__attributesOriginal910788ea410fffefedcbd7bf359e6239); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal910788ea410fffefedcbd7bf359e6239)): ?>
<?php $component = $__componentOriginal910788ea410fffefedcbd7bf359e6239; ?>
<?php unset($__componentOriginal910788ea410fffefedcbd7bf359e6239); ?>
<?php endif; ?>

            <?php
                $currentLevel = (int) ($profile['level'] ?? 1);
                $totalLevels = 8;
            ?>
            <div class="w-full overflow-hidden mt-6">
                <div class="flex md:justify-between items-center overflow-x-auto md:overflow-x-visible pb-3 md:pb-0 gap-4 md:gap-2 no-scrollbar flex-nowrap relative">
                    <?php for($level = 1; $level <= $totalLevels; $level++): ?>
                        <div class="flex flex-col items-center gap-2 min-w-[75px] md:min-w-0 flex-shrink-0">
                            <?php if($level < $currentLevel): ?>
                                <div class="w-4 h-4 rounded-full border-2 bg-accent" style="border-color:hsl(var(--color-surface));box-shadow:0 0 0 2px hsl(var(--color-accent)/0.20);"></div>
                                <span class="text-[11px] font-bold text-accent">المستوى <?php echo e($level); ?></span>
                                <span class="text-[9px] font-medium text-text-muted">مكتمل</span>
                            <?php elseif($level == $currentLevel): ?>
                                <div class="w-5 h-5 rounded-full border-2 bg-primary" style="border-color:hsl(var(--color-surface));box-shadow:0 0 0 4px hsl(var(--color-primary)/0.15);"></div>
                                <span class="text-[11px] font-black text-primary">المستوى <?php echo e($level); ?></span>
                                <span class="text-[9px] font-medium text-text-muted">الحالي</span>
                            <?php else: ?>
                                <div class="w-4 h-4 rounded-full border-2 bg-border" style="border-color:hsl(var(--color-border));"></div>
                                <span class="text-[11px] font-semibold text-text-muted">المستوى <?php echo e($level); ?></span>
                                <span class="text-[9px] font-medium text-text-muted">قادم</span>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\New folder\رفيق الطالب\resources\views/academic/dashboard.blade.php ENDPATH**/ ?>