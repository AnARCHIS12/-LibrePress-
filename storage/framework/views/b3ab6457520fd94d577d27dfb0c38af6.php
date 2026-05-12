<?php $__env->startSection('admin'); ?>
    <h1>Tableau de bord</h1>
    <div class="grid">
        <div class="card"><h2><?php echo e($pages); ?></h2><p class="muted">Pages</p></div>
        <div class="card"><h2><?php echo e($posts); ?></h2><p class="muted">Articles</p></div>
        <div class="card"><h2><?php echo e($drafts); ?></h2><p class="muted">Brouillons</p></div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>