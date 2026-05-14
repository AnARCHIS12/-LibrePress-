<?php $__env->startSection('admin'); ?>
    <h1>Sante systeme</h1>

    <div class="grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $checks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="card">
                <h2><?php echo e($name); ?></h2>
                <p>Statut: <?php echo e($check['status']); ?></p>
                <p class="muted"><?php echo e($check['detail']); ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <form method="post" action="<?php echo e(route('admin.system.cache.clear')); ?>" class="card" style="margin-top: 20px">
        <?php echo csrf_field(); ?>
        <h2>Cache</h2>
        <p class="muted">Vide les caches applicatifs et publics.</p>
        <button class="primary" type="submit">Vider le cache</button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/system/index.blade.php ENDPATH**/ ?>