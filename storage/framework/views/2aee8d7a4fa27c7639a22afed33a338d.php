<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'description' => null,
    'variant' => 'default',
    'padding' => true,
    'header' => null,
    'footer' => null,
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
    'title' => null,
    'description' => null,
    'variant' => 'default',
    'padding' => true,
    'header' => null,
    'footer' => null,
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
    'default'  => 'card',
    'elevated' => 'card-elevated',
    'navy'     => 'card-navy',
    'primary'  => 'card-primary',
    'info'     => 'card-info',
    'success'  => 'card-success',
    'warning'  => 'card-warning',
    'error'    => 'card-error',
];

$paddingClass = $padding ? 'p-4 md:p-5' : '';
?>

<div <?php echo e($attributes->merge(['class' => 'rf-card dashboard-card ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . $paddingClass])); ?>>
    <?php if($header): ?>
        <div class="rf-card-header <?php echo e($padding ? '-mx-4 md:-mx-5 px-4 md:px-5 pb-4 md:pb-5 border-b border-border' : 'mb-4'); ?>">
            <?php echo e($header); ?>

        </div>
    <?php elseif($title || $description): ?>
        <div class="rf-card-header mb-4">
            <?php if($title): ?>
                <h3 class="rf-card-title heading-premium"><?php echo e($title); ?></h3>
            <?php endif; ?>
            <?php if($description): ?>
                <p class="rf-card-description text-sm text-text-secondary mt-1"><?php echo e($description); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="rf-card-body">
        <?php echo e($slot); ?>

    </div>

    <?php if($footer): ?>
        <div class="rf-card-footer mt-4 <?php echo e($padding ? '-mx-4 md:-mx-5 px-4 md:px-5 pt-4 md:pt-5 border-t border-border' : ''); ?>">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/rf-card.blade.php ENDPATH**/ ?>