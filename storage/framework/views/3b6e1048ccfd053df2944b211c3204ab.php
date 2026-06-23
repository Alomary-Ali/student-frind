<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'description' => '',
    'icon' => null,
    'action' => null,
    'actionLabel' => null,
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
    'title' => '',
    'description' => '',
    'icon' => null,
    'action' => null,
    'actionLabel' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'rf-empty-state flex flex-col items-center justify-center text-center py-12 px-4'])); ?>>
    <?php if($icon): ?>
        <div class="rf-empty-icon mb-4 text-text-muted/50" aria-hidden="true">
            <?php echo $icon; ?>

        </div>
    <?php else: ?>
        <div class="rf-empty-icon mb-4 text-text-muted/30" aria-hidden="true">
            <svg class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
    <?php endif; ?>

    <?php if($title): ?>
        <h3 class="rf-empty-title text-lg font-bold text-text-primary mb-1"><?php echo e($title); ?></h3>
    <?php endif; ?>

    <?php if($description): ?>
        <p class="rf-empty-description text-sm text-text-secondary max-w-xs"><?php echo e($description); ?></p>
    <?php endif; ?>

    <?php if($action && $actionLabel): ?>
        <div class="rf-empty-action mt-5">
            <?php echo e($action); ?>

        </div>
    <?php endif; ?>

    <?php echo e($slot); ?>

</div>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/rf-empty-state.blade.php ENDPATH**/ ?>