<?php $__env->startSection('admin'); ?>
    <h1>Reglages</h1>

    <form method="post" action="<?php echo e(route('admin.settings.update')); ?>" class="card">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>

        <label>
            Nom du site
            <input name="site_name" value="<?php echo e(old('site_name', $siteName)); ?>" required>
        </label>

        <label>
            Description
            <input name="site_description" value="<?php echo e(old('site_description', $siteDescription)); ?>">
        </label>

        <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
            <input name="comments_enabled" type="checkbox" value="1" <?php if($commentsEnabled): echo 'checked'; endif; ?> style="width: auto">
            Commentaires publics
        </label>

        <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
            <input name="activitypub_enabled" type="checkbox" value="1" <?php if($activitypubEnabled): echo 'checked'; endif; ?> style="width: auto">
            ActivityPub experimental
        </label>

        <button class="primary" type="submit">Enregistrer</button>
    </form>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/admin/settings/edit.blade.php ENDPATH**/ ?>