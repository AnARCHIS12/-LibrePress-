<?php $__env->startSection('admin'); ?>
    <div class="form-actions" style="justify-content: space-between; margin-bottom: 16px">
        <h1>Contenus</h1>
        <a class="button primary" href="<?php echo e(route('admin.pages.create')); ?>">Nouveau</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Slug</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($content->title); ?></td>
                    <td><?php echo e($content->type); ?></td>
                    <td><?php echo e($content->status); ?></td>
                    <td><?php echo e($content->slug); ?></td>
                    <td>
                        <a class="button" href="<?php echo e(route('admin.pages.edit', $content)); ?>">Editer</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 24px"><?php echo e($contents->links()); ?></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/pages/index.blade.php ENDPATH**/ ?>