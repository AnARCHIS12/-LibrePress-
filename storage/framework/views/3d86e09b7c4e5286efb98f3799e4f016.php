<?php $__env->startSection('admin'); ?>
    <h1>Preview theme: <?php echo e($theme['name']); ?></h1>

    <div class="card">
        <p>Slug: <?php echo e($theme['slug']); ?></p>
        <p>Version: <?php echo e($theme['version']); ?></p>
        <p>Compatibilite: <?php echo e($compatible ? 'ok' : 'incompatible'); ?></p>
        <p>Checksum: <?php echo e($checksum); ?></p>

        <h2>Regions</h2>
        <ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($theme['regions'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($region); ?>: <?php echo e($label); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/themes/preview.blade.php ENDPATH**/ ?>