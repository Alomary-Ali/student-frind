<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'dot' => false,
    'pill' => true,
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
    'variant' => 'primary',
    'size' => 'md',
    'dot' => false,
    'pill' => true,
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
    'primary'   => 'badge-primary',
    'accent'    => 'badge-accent',
    'warning'   => 'badge-warning',
    'error'     => 'badge-error',
    'navy'      => 'badge-navy',
    'muted'     => 'badge-muted',
];

$sizeClasses = [
    'sm' => 'text-[10px] px-1.5 py-0.5',
    'md' => '',
];
?>

<span <?php echo e($attributes->merge([
        'class' => 'rf-badge badge '
            . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' '
            . ($sizeClasses[$size] ?? $sizeClasses['md']),
    ])); ?>>
    <?php if($dot): ?>
        <span class="rf-badge-dot status-dot <?php echo e(match($variant) {
            'primary' => 'bg-primary',
            'accent' => 'bg-accent',
            'warning' => 'bg-warning',
            'error' => 'bg-error',
            'navy' => 'bg-navy',
            default => 'bg-primary',
        }); ?>" aria-hidden="true"></span>
    <?php endif; ?>
    <?php echo e($slot); ?>

</span>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/rf-badge.blade.php ENDPATH**/ ?>