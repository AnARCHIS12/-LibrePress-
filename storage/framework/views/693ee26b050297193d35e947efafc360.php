<?php $__env->startSection('body'); ?>
    <main class="shell content">
        <h1>Recherche</h1>
        <form method="get" action="<?php echo e(route('front.search')); ?>" class="card" style="margin-bottom: 20px">
            <label>
                Terme
                <input name="q" value="<?php echo e($query); ?>" autofocus>
            </label>
            <button class="primary" type="submit">Rechercher</button>
        </form>

        <div class="grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="card">
                    <h2><a href="<?php echo e(route('front.show', $result->slug)); ?>"><?php echo e($result->title); ?></a></h2>
                    <p class="muted"><?php echo e($result->excerpt); ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </main>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/search.blade.php ENDPATH**/ ?>