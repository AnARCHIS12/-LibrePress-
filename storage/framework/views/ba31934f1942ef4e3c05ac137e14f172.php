<?php $__env->startSection('body'); ?>
    <main class="shell content">
        <article class="prose">
            <h1><?php echo e($content->title); ?></h1>
            <?php echo $renderedBlocks; ?>

        </article>

        <section class="content prose">
            <h2>Commentaires</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $content->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card" style="margin-bottom: 12px">
                    <strong><?php echo e($comment->author_name); ?></strong>
                    <p><?php echo e($comment->body); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted">Aucun commentaire pour le moment.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="post" action="<?php echo e(route('comments.store', $content)); ?>" class="card">
                <?php echo csrf_field(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                    <label>
                        Nom
                        <input name="author_name" required>
                    </label>
                    <label>
                        Email
                        <input name="author_email" type="email">
                    </label>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <label>
                    Commentaire
                    <textarea name="body" required style="min-height: 120px"></textarea>
                </label>
                <button class="primary" type="submit">Publier</button>
            </form>
        </section>
    </main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/page.blade.php ENDPATH**/ ?>