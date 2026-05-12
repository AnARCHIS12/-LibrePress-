<?php $__env->startSection('body'); ?>
    <main class="shell content">
        <h1>Blog</h1>
        <div class="grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="card">
                    <h2><a href="<?php echo e(route('front.show', $post->slug)); ?>"><?php echo e($post->title); ?></a></h2>
                    <p class="muted"><?php echo e($post->excerpt); ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div style="margin-top: 24px"><?php echo e($posts->links()); ?></div>
    </main>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/blog.blade.php ENDPATH**/ ?>