<?php $__env->startSection('admin'); ?>
    <h1>Commentaires</h1>

    <table>
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Contenu</th>
                <th>Statut</th>
                <th>Commentaire</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($comment->author_name); ?></td>
                    <td><?php echo e($comment->content?->title); ?></td>
                    <td><?php echo e($comment->status); ?></td>
                    <td><?php echo e($comment->body); ?></td>
                    <td>
                        <div class="form-actions">
                            <form method="post" action="<?php echo e(route('admin.comments.approve', $comment)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('patch'); ?>
                                <button type="submit">Approuver</button>
                            </form>
                            <form method="post" action="<?php echo e(route('admin.comments.reject', $comment)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('patch'); ?>
                                <button type="submit">Rejeter</button>
                            </form>
                            <form method="post" action="<?php echo e(route('admin.comments.destroy', $comment)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('delete'); ?>
                                <button class="danger" type="submit">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 24px"><?php echo e($comments->links()); ?></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/comments/index.blade.php ENDPATH**/ ?>