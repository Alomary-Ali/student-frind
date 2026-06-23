<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'userName' => 'طالب رفيق',
    'gpa' => 0,
    'progress' => 0,
    'readiness' => 0,
    'skills' => null,
    'courses' => null,
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
    'userName' => 'طالب رفيق',
    'gpa' => 0,
    'progress' => 0,
    'readiness' => 0,
    'skills' => null,
    'courses' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="pulsebar" id="pulsebar">
    <div class="pulsebar-inner">

        
        <div class="pulsebar-item">
            <div class="pulsebar-dot"></div>
            <span class="pulsebar-name" id="pulsebar-name"><?php echo e($userName); ?></span>
        </div>

        <div class="pulsebar-divider"></div>

        
        <div class="pulsebar-item">
            <span class="pulsebar-label">المعدل</span>
            <span class="pulsebar-value" id="pulsebar-gpa"><?php echo e(number_format($gpa, 2)); ?></span>
        </div>

        <div class="pulsebar-divider"></div>

        
        <div class="pulsebar-item">
            <span class="pulsebar-label">التقدم</span>
            <span class="pulsebar-value" id="pulsebar-progress"><?php echo e($progress); ?>%</span>
        </div>

        
        <div class="pulsebar-tablet">
            <div class="pulsebar-divider"></div>
            <div class="pulsebar-item">
                <span class="pulsebar-label">الجاهزية</span>
                <span class="pulsebar-value pulsebar-value-accent" id="pulsebar-readiness"><?php echo e($readiness); ?>%</span>
            </div>
        </div>

        
        <?php if(!is_null($skills) || !is_null($courses)): ?>
            <div class="pulsebar-desktop">
                <div class="pulsebar-divider"></div>
                <?php if(!is_null($skills)): ?>
                    <div class="pulsebar-item">
                        <span class="pulsebar-label">المهارات</span>
                        <span class="pulsebar-value" id="pulsebar-skills"><?php echo e($skills); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(!is_null($courses)): ?>
                    <div class="pulsebar-item mr-4">
                        <span class="pulsebar-label">المواد</span>
                        <span class="pulsebar-value" id="pulsebar-courses"><?php echo e($courses); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    const elName = document.getElementById('pulsebar-name');
    const elGpa  = document.getElementById('pulsebar-gpa');
    const elProg = document.getElementById('pulsebar-progress');
    const elRead = document.getElementById('pulsebar-readiness');
    const elSkil = document.getElementById('pulsebar-skills');
    const elCour = document.getElementById('pulsebar-courses');

    if (!elGpa) return;

    let interval = setInterval(fetchPulseBar, 30000);

    function fetchPulseBar() {
        fetch('/api/pulsebar/data')
            .then(r => r.json())
            .then(d => {
                if (elName) elName.textContent = d.userName;
                if (elGpa)  elGpa.textContent = Number(d.gpa).toFixed(2);
                if (elProg) elProg.textContent = d.progress + '%';
                if (elRead) elRead.textContent = d.readiness + '%';
                if (elSkil) elSkil.textContent = d.skills;
                if (elCour) elCour.textContent = d.courses;
            })
            .catch(() => {});
    }
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\New folder\رفيق الطالب\resources\views/components/pulse-bar.blade.php ENDPATH**/ ?>