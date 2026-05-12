<?php $__env->startSection('admin'); ?>
    <h1>Medias</h1>
    <form class="card" method="post" action="<?php echo e(route('admin.media.store')); ?>" enctype="multipart/form-data" style="margin-bottom: 20px">
        <?php echo csrf_field(); ?>
        <label>
            Fichier
            <input name="file" type="file" required>
        </label>
        <label>
            Texte alternatif
            <input name="alt">
        </label>
        <button class="primary" type="submit">Ajouter</button>
    </form>

    <div class="grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_starts_with($item->mime_type, 'image/')): ?>
                    <img src="<?php echo e($item->url()); ?>" alt="<?php echo e($item->alt); ?>">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p><?php echo e($item->path); ?></p>
                <form method="post" action="<?php echo e(route('admin.media.destroy', $item)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('delete'); ?>
                    <button class="danger" type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div style="margin-top: 24px"><?php echo e($media->links()); ?></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/media/index.blade.php ENDPATH**/ ?>