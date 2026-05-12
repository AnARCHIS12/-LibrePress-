<?php $__env->startSection('admin'); ?>
    <h1>Themes</h1>

    <div class="grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="card">
                <h2><?php echo e($theme['name']); ?></h2>
                <p class="muted"><?php echo e($theme['description'] ?? 'Theme local'); ?></p>
                <p>Version <?php echo e($theme['version']); ?></p>
                <p>Statut: <?php echo e($theme['record']?->enabled ? 'actif' : 'inactif'); ?></p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($theme['record']?->enabled)): ?>
                    <form method="post" action="<?php echo e(route('admin.themes.activate', $theme['slug'])); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="primary" type="submit">Activer</button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/themes/index.blade.php ENDPATH**/ ?>