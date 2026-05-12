<?php $__env->startSection('body'); ?>
    <main class="shell">
        <section class="hero">
            <h1><?php echo e($content?->title ?? 'LibrePress'); ?></h1>
            <p><?php echo e($content?->excerpt ?? 'CMS Laravel libre, modulaire, leger et auto-hebergeable.'); ?></p>
            <a class="button primary" href="<?php echo e(route('front.blog')); ?>">Lire le blog</a>
        </section>

        <section class="content prose">
            <?php echo $renderedBlocks; ?>

        </section>

        <section class="content">
            <h2>Derniers articles</h2>
            <div class="grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="card">
                        <h3><a href="<?php echo e(route('front.show', $post->slug)); ?>"><?php echo e($post->title); ?></a></h3>
                        <p class="muted"><?php echo e($post->excerpt); ?></p>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="muted">Aucun article publie.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>
    </main>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/home.blade.php ENDPATH**/ ?>