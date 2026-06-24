<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'رفيق الطالب'); ?> | منصة نجاح الطالب</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>

    <?php $isHome = request()->routeIs('home'); ?>

    <style>
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: hsl(var(--color-navy) / 0.45);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 50;
        }
        #sidebar {
            z-index: 51;
        }
    </style>
</head>
<body class="min-h-screen bg-background text-text-primary flex flex-col" style="font-family: 'Cairo', sans-serif;">


<?php
    use App\Services\PulseBarDataResolver;

    $plsStats = app(PulseBarDataResolver::class)->resolve((string) auth()->id());
    $plsName = auth()->check()
        ? (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '')
        : 'طالب رفيق';
?>
<?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => ['variant' => 'dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'dashboard']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<div class="navbar-spacer"></div>
<?php if(!$isHome): ?>
<?php if (isset($component)) { $__componentOriginalda48f43fb86ec11b0c7015474767511f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda48f43fb86ec11b0c7015474767511f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pulse-bar','data' => ['userName' => $plsName,'gpa' => $plsStats['gpa'],'progress' => $plsStats['progress'],'readiness' => $plsStats['readiness'],'skills' => $plsStats['skills'],'courses' => $plsStats['courses']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pulse-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['userName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plsName),'gpa' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plsStats['gpa']),'progress' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plsStats['progress']),'readiness' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plsStats['readiness']),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plsStats['skills']),'courses' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plsStats['courses'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda48f43fb86ec11b0c7015474767511f)): ?>
<?php $attributes = $__attributesOriginalda48f43fb86ec11b0c7015474767511f; ?>
<?php unset($__attributesOriginalda48f43fb86ec11b0c7015474767511f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda48f43fb86ec11b0c7015474767511f)): ?>
<?php $component = $__componentOriginalda48f43fb86ec11b0c7015474767511f; ?>
<?php unset($__componentOriginalda48f43fb86ec11b0c7015474767511f); ?>
<?php endif; ?>
<div class="pulsebar-spacer"></div>
<?php endif; ?>


<div id="sidebar-overlay" onclick="closeSidebar()" style="display:none;"></div>

    <div class="flex flex-1 min-h-0">

    
    <aside id="sidebar"
           class="w-[280px] max-w-[85vw] flex flex-col
                  fixed <?php echo e($isHome ? 'top-[48px] md:top-[56px]' : 'top-[84px] md:top-[96px]'); ?> right-0 h-full z-[45]
                  translate-x-full lg:translate-x-0
                  transition-transform duration-300 ease-in-out
                  lg:sticky lg:self-start lg:z-[45] lg:flex-shrink-0 lg:w-[240px] lg:max-w-none min-h-0
                  <?php echo e($isHome ? 'lg:top-[56px] lg:h-[calc(100vh-56px)]' : 'lg:top-[96px] lg:h-[calc(100vh-96px)]'); ?>">

        
        <div class="sidebar-logo-area">
            <div class="sidebar-logo-mark">ر</div>
            <div class="min-w-0">
                <p class="text-sm font-black leading-tight truncate text-text-primary">رفيق الطالب</p>
                <p class="text-[10px] leading-tight mt-0.5 text-text-muted">منصة نجاح الطالب</p>
            </div>
        </div>

        
        <div class="sidebar-user-area">
            <div class="user-avatar">
                <?php echo e(mb_substr(auth()->user()->first_name ?? 'ط', 0, 1)); ?>

            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[12.5px] font-bold truncate leading-tight text-text-primary">
                    <?php echo e(auth()->user()->first_name ?? ''); ?> <?php echo e(auth()->user()->last_name ?? ''); ?>

                </p>
                <p class="text-[10px] mt-0.5 text-text-muted"><?php echo e(auth()->user()->academic_id ?? ''); ?></p>
            </div>
            <div class="status-pill">
                <div class="status-pill-dot"></div>
                نشط
            </div>
        </div>

        
        <nav class="flex-1 px-3 py-2 overflow-y-auto no-scrollbar">

            
            <a href="<?php echo e(route('home')); ?>"
               class="nav-link <?php echo e(request()->routeIs('home') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('home') ? 'aria-current="page"' : ''); ?>

               data-nav-link="home">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                الرئيسية
            </a>

            
            <p class="nav-section-label">الأكاديمي</p>

            <a href="<?php echo e(route('academic.dashboard')); ?>"
               class="nav-link <?php echo e(request()->routeIs('academic.dashboard') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('academic.dashboard') ? 'aria-current="page"' : ''); ?>

               data-nav-link="academic-dashboard">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                لوحة القيادة
            </a>

            <a href="<?php echo e(route('academic.courses')); ?>"
               class="nav-link <?php echo e(request()->routeIs('academic.courses') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('academic.courses') ? 'aria-current="page"' : ''); ?>

               data-nav-link="academic-courses">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                المواد الدراسية
            </a>

            <a href="<?php echo e(route('academic.plan')); ?>"
               class="nav-link <?php echo e(request()->routeIs('academic.plan') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('academic.plan') ? 'aria-current="page"' : ''); ?>

               data-nav-link="academic-plan">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                الخطة الدراسية
            </a>

            <a href="<?php echo e(route('academic.profile')); ?>"
               class="nav-link <?php echo e(request()->routeIs('academic.profile') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('academic.profile') ? 'aria-current="page"' : ''); ?>

               data-nav-link="academic-profile">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                الملف الأكاديمي
            </a>

            <a href="<?php echo e(route('academic.progress')); ?>"
               class="nav-link <?php echo e(request()->routeIs('academic.progress') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('academic.progress') ? 'aria-current="page"' : ''); ?>

               data-nav-link="academic-progress">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
                مؤشرات الأداء
            </a>

            <a href="<?php echo e(route('academic.graduation-map')); ?>"
               class="nav-link <?php echo e(request()->routeIs('academic.graduation-map') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('academic.graduation-map') ? 'aria-current="page"' : ''); ?>

               data-nav-link="academic-graduation-map">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l5.447-2.724A1 1 0 0015 16.382V5.618a1 1 0 00-1.447-.894L9 7m0 13V7"/>
                </svg>
                خريطة التخرج
            </a>

            
            <p class="nav-section-label">الإنتاجية</p>

            <a href="<?php echo e(route('productivity.dashboard')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.dashboard') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.dashboard') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-dashboard">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
                لوحة الإنتاجية
            </a>

            <a href="<?php echo e(route('productivity.goals')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.goals*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.goals*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-goals">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                الأهداف
            </a>

            <a href="<?php echo e(route('productivity.tasks')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.tasks*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.tasks*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-tasks">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                المهام
            </a>

            <a href="<?php echo e(route('productivity.calendar')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.calendar*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.calendar*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-calendar">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                التقويم
            </a>

            <a href="<?php echo e(route('productivity.reminders')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.reminders*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.reminders*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-reminders">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                التذكيرات
            </a>

            <a href="<?php echo e(route('productivity.assignments.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.assignments*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.assignments*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-assignments">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                الواجبات
            </a>

            <a href="<?php echo e(route('productivity.exams.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.exams*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.exams*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-exams">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                الامتحانات
            </a>

            <a href="<?php echo e(route('productivity.projects.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('productivity.projects*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('productivity.projects*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="productivity-projects">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                المشاريع
            </a>

            
            <p class="nav-section-label">خدمات الطالب</p>

            <a href="<?php echo e(route('student-services.dashboard')); ?>"
               class="nav-link <?php echo e(request()->routeIs('student-services.dashboard') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('student-services.dashboard') ? 'aria-current="page"' : ''); ?>

               data-nav-link="student-services-dashboard">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                لوحة الخدمات
            </a>

            <a href="<?php echo e(route('student-services.requests.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('student-services.requests*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('student-services.requests*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="student-services-requests">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                طلباتي
            </a>

            <a href="<?php echo e(route('student-services.documents.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('student-services.documents*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('student-services.documents*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="student-services-documents">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                المستندات
            </a>

            <a href="<?php echo e(route('student-services.knowledge.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('student-services.knowledge*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('student-services.knowledge*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="student-services-knowledge">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                قاعدة المعرفة
            </a>

            <a href="<?php echo e(route('student-services.faq.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('student-services.faq*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('student-services.faq*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="student-services-faq">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                الأسئلة الشائعة
            </a>

            <a href="<?php echo e(route('student-services.assistant.chat')); ?>"
               class="nav-link <?php echo e(request()->routeIs('student-services.assistant*') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('student-services.assistant*') ? 'aria-current="page"' : ''); ?>

               data-nav-link="student-services-assistant">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                المساعد الذكي
            </a>

            <?php if(auth()->user() && auth()->user()->role === 'student'): ?>
            
            <p class="nav-section-label">التطوير المهني والمهارات</p>

            <a href="<?php echo e(route('career.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('career.index') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('career.index') ? 'aria-current="page"' : ''); ?>

               data-nav-link="career-index">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                الملف المهني
            </a>

            <a href="<?php echo e(route('skills.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('skills.index') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('skills.index') ? 'aria-current="page"' : ''); ?>

               data-nav-link="skills-index">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                المهارات والشهادات
            </a>

            
            <p class="nav-section-label mt-4">مركز الفرص</p>

            <a href="<?php echo e(route('opportunities.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('opportunities.index') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('opportunities.index') ? 'aria-current="page"' : ''); ?>

               data-nav-link="opportunities-index">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                جميع الفرص
            </a>

            <a href="<?php echo e(route('opportunities.recommended')); ?>"
               class="nav-link <?php echo e(request()->routeIs('opportunities.recommended') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('opportunities.recommended') ? 'aria-current="page"' : ''); ?>

               data-nav-link="opportunities-recommended">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                الموصى بها
            </a>

            <a href="<?php echo e(route('opportunities.saved')); ?>"
               class="nav-link <?php echo e(request()->routeIs('opportunities.saved') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('opportunities.saved') ? 'aria-current="page"' : ''); ?>

               data-nav-link="opportunities-saved">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                المحفوظة
            </a>

            <a href="<?php echo e(route('opportunities.applications')); ?>"
               class="nav-link <?php echo e(request()->routeIs('opportunities.applications') ? 'nav-link-active' : ''); ?>"
               <?php echo e(request()->routeIs('opportunities.applications') ? 'aria-current="page"' : ''); ?>

               data-nav-link="opportunities-applications">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                طلبات التقديم
            </a>
            <?php endif; ?>

        </nav>

        
        <div class="sidebar-bottom space-y-0.5">
            <button onclick="toggleTheme()" class="nav-link w-full text-right">
                <svg class="h-4 w-4 shrink-0 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="h-4 w-4 shrink-0 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="dark:hidden">الوضع المظلم</span>
                <span class="hidden dark:inline">الوضع الفاتح</span>
            </button>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link nav-link-danger w-full text-right text-error">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

        
    <div id="main-content" class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

        
        <main class="flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto min-h-0">
            <?php if(session('success')): ?>
                <div class="rounded-2xl p-4 mb-6" style="background:hsl(var(--color-accent)/0.10);border:1px solid hsl(var(--color-accent)/0.20);">
                    <p class="font-bold text-sm" style="color:hsl(var(--color-accent));"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="rounded-2xl p-4 mb-6" style="background:hsl(var(--color-error)/0.10);border:1px solid hsl(var(--color-error)/0.20);">
                    <p class="font-bold text-sm" style="color:hsl(var(--color-error));"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="rounded-2xl p-4 mb-6" style="background:hsl(var(--color-error)/0.10);border:1px solid hsl(var(--color-error)/0.20);">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p class="font-bold text-sm" style="color:hsl(var(--color-error));"><?php echo e($err); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

    </div>
</div>

<script>
    // ── Theme ──
    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    (function initTheme() {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    })();

    // ── Mobile sidebar ──
    function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.remove('translate-x-full');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        // Small delay for overlay to fade in
        requestAnimationFrame(() => overlay.style.opacity = '1');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.add('translate-x-full');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close sidebar on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });

    // Close sidebar when clicking nav links on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('#sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Auto-scroll active nav link into view
        var activeLink = document.querySelector('#sidebar .nav-link-active');
        if (activeLink) {
            activeLink.scrollIntoView({ block: 'nearest', behavior: 'instant' });
        }
    });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>