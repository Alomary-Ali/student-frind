<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'loading' => false,
    'icon' => null,
    'fullWidth' => false,
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
    'type' => 'button',
    'loading' => false,
    'icon' => null,
    'fullWidth' => false,
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
    'primary'    => 'btn-primary',
    'secondary'  => 'btn-secondary',
    'accent'     => 'btn-accent',
    'ghost'      => 'btn-ghost',
    'destructive' => 'btn btn-sm !bg-error !text-surface hover:!bg-error-hover',
];

$sizeClasses = [
    'sm' => 'btn-sm',
    'md' => '',
    'lg' => 'h-12 px-8 text-base',
];
?>

<button
    type="<?php echo e($type); ?>"
    <?php echo e($attributes->merge([
            'class' => 'btn rf-btn '
                . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' '
                . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' '
                . ($fullWidth ? 'btn-full ' : '')
                . ($loading ? 'opacity-70 pointer-events-none ' : ''),
        ])); ?>

    <?php if($loading): ?> disabled aria-busy="true" <?php endif; ?>
>
    <?php if($icon && !$loading): ?>
        <span class="rf-btn-icon" aria-hidden="true"><?php echo $icon; ?></span>
    <?php endif; ?>
    <?php if($loading): ?>
        <svg class="animate-spin -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span><?php echo e($slot); ?></span>
    <?php else: ?>
        <?php echo e($slot); ?>

    <?php endif; ?>
</button>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/rf-button.blade.php ENDPATH**/ ?>