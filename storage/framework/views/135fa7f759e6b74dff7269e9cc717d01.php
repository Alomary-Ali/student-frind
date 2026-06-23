<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'normal',
    'title' => null,
    'dismissible' => false,
    'icon' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'variant' => 'normal',
    'title' => null,
    'dismissible' => false,
    'icon' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$variantClasses = [
    'normal'   => 'alert-normal',
    'high'     => 'alert-high',
    'critical' => 'alert-critical',
    'success'  => '!bg-accent/10 !border-accent',
    'info'     => 'alert-normal',
];

$variantIcons = [
    'normal'   => null,
    'high'     => '<svg class="w-5 h-5 shrink-0 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'critical' => '<svg class="w-5 h-5 shrink-0 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'success'  => '<svg class="w-5 h-5 shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'info'     => '<svg class="w-5 h-5 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
];
?>

<div
    x-data="{ visible: true }"
    x-show="visible"
    x-transition:leave="transition ease-out duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    role="alert"
    <?php echo e($attributes->merge([
            'class' => 'rf-alert relative flex items-start gap-3 p-4 rounded-2xl border '
                . ($variantClasses[$variant] ?? $variantClasses['normal'])
                . ($dismissible ? ' pe-12' : ''),
        ])); ?>

>
    <?php if($icon): ?>
        <span class="rf-alert-icon shrink-0" aria-hidden="true"><?php echo $icon; ?></span>
    <?php elseif($variantIcons[$variant] ?? null): ?>
        <span class="rf-alert-icon shrink-0"><?php echo $variantIcons[$variant]; ?></span>
    <?php endif; ?>

    <div class="rf-alert-content flex-1 min-w-0">
        <?php if($title): ?>
            <p class="rf-alert-title font-bold text-sm text-text-primary"><?php echo e($title); ?></p>
        <?php endif; ?>
        <div class="rf-alert-body text-sm text-text-secondary">
            <?php echo e($slot); ?>

        </div>
    </div>

    <?php if($dismissible): ?>
        <button
            type="button"
            @click="visible = false"
            class="rf-alert-close absolute top-3 end-3 p-1 rounded-lg text-text-muted hover:text-text-primary hover:bg-surface/50 transition-colors"
            aria-label="إغلاق التنبيه"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    <?php endif; ?>
</div>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/rf-alert.blade.php ENDPATH**/ ?>