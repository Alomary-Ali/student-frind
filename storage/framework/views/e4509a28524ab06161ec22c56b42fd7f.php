<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> | رفيق الطالب</title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'منصة النجاح الأكاديمي والمهني'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, hsl(var(--color-primary) / 0.07) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, hsl(var(--color-accent) / 0.05) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 60% 40%, hsl(var(--color-warning) / 0.03) 0%, transparent 50%),
                hsl(var(--color-background));
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-card {
            position: relative;
            z-index: 1;
            will-change: transform;
        }

        .rafiq-input {
            width: 100%;
            height: 52px;
            background: hsl(var(--color-background));
            border: 1.5px solid hsl(var(--color-border));
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            color: hsl(var(--color-text-primary));
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            outline: none;
            font-family: 'Cairo', sans-serif;
        }

        .rafiq-input:focus {
            border-color: hsl(var(--color-primary));
            background: hsl(var(--color-surface));
            box-shadow: 0 0 0 4px hsl(var(--color-primary) / 0.08);
        }

        .rafiq-input::placeholder {
            color: hsl(var(--color-text-muted));
            font-weight: 400;
        }

        .rafiq-input.has-error {
            border-color: hsl(var(--color-error));
            box-shadow: 0 0 0 4px hsl(var(--color-error) / 0.08);
        }

        .logo-ring {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, hsl(var(--color-primary)) 0%, hsl(var(--color-navy)) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px hsl(var(--color-primary) / 0.35), 0 2px 6px hsl(var(--color-navy) / 0.15);
            position: relative;
            overflow: hidden;
        }

        .logo-ring::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(180deg, hsl(var(--color-surface) / 0.15) 0%, transparent 100%);
            border-radius: inherit;
        }
    </style>
    <script>
        (function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-background text-text-primary" style="font-family: 'Cairo', sans-serif;">

    <div class="bg-mesh"></div>

    
    <div class="shape w-72 h-72 bg-primary/5" style="top:-60px;right:-60px;animation:float 8s ease-in-out infinite;"></div>
    <div class="shape w-48 h-48 bg-accent/5" style="bottom:-40px;left:-40px;animation:float 10s ease-in-out infinite;animation-delay:2s;"></div>

    <div class="auth-card w-full max-w-[420px] animate-scale-in">
        <div class="rounded-[28px] overflow-hidden shadow-lg border bg-surface border-border">

            <div class="h-1.5 w-full opacity-85" style="background: linear-gradient(to right, hsl(var(--color-primary)), hsl(var(--color-primary-hover)), hsl(var(--color-navy)));"></div>

            <div class="p-6 sm:p-8">
                <?php echo $__env->yieldContent('auth-content'); ?>
            </div>

            <div class="px-8 py-4 flex items-center justify-center border-t border-border-light">
                <span class="text-[9px] font-black text-text-muted uppercase tracking-[0.2em]">Rafiq © 2026</span>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH E:\New folder\رفيق الطالب\resources\views/layouts/auth.blade.php ENDPATH**/ ?>