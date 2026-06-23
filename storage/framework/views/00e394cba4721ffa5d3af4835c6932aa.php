<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'label' => null,
    'showPercentage' => true,
    'size' => 'md',
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
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'label' => null,
    'showPercentage' => true,
    'size' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$percentage = min(100, max(0, ($value / max(1, $max)) * 100));
$fillClass = match($variant) {
    'primary' => 'progress-fill-primary',
    'accent' => 'progress-fill-accent',
    'warning' => 'progress-fill-warning',
    'gradient' => 'progress-fill-gradient',
    default => 'progress-fill-primary',
};
$heightClass = match($size) {
    'sm' => 'h-1',
    'md' => 'h-1.5',
    'lg' => 'h-2.5',
    default => 'h-1.5',
};
?>

<div <?php echo e($attributes->merge(['class' => 'rf-progress w-full'])); ?>>
    <?php if($label || $showPercentage): ?>
        <div class="rf-progress-header flex items-center justify-between mb-1.5">
            <?php if($label): ?>
                <span class="rf-progress-label text-xs font-bold text-text-secondary"><?php echo e($label); ?></span>
            <?php endif; ?>
            <?php if($showPercentage): ?>
                <span class="rf-progress-value text-xs font-bold text-text-muted" role="progressbar" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo e(round($percentage)); ?>%
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="rf-progress-track progress-track <?php echo e($heightClass); ?>" role="progressbar" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100">
        <div class="rf-progress-fill progress-fill <?php echo e($fillClass); ?>" style="width: <?php echo e($percentage); ?>%"></div>
    </div>
</div>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/rf-progress.blade.php ENDPATH**/ ?>