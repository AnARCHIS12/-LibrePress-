<?php $__env->startSection('body'); ?>
    <div class="admin-layout">
        <aside class="sidebar">
            <strong>Administration</strong>
            <a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a>
            <a href="<?php echo e(route('admin.pages.index')); ?>">Contenus</a>
            <a href="<?php echo e(route('admin.media.index')); ?>">Medias</a>
            <a href="<?php echo e(route('admin.comments.index')); ?>">Commentaires</a>
            <a href="<?php echo e(route('admin.modules.index')); ?>">Modules</a>
            <a href="<?php echo e(route('admin.themes.index')); ?>">Themes</a>
            <a href="<?php echo e(route('admin.settings.edit')); ?>">Reglages</a>
            <a href="<?php echo e(route('front.home')); ?>">Voir le site</a>
        </aside>
        <main class="admin-main">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <p class="notice"><?php echo e(session('status')); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($errors) && $errors->any()): ?>
                <div class="notice">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo $__env->yieldContent('admin'); ?>
        </main>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/layouts/admin.blade.php ENDPATH**/ ?>