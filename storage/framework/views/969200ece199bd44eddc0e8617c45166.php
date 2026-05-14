<?xml version="1.0" encoding="UTF-8" ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title><?php echo e(config('app.name')); ?></title>
    <id><?php echo e(url('/')); ?></id>
    <link href="<?php echo e(url('/')); ?>" />
    <link rel="self" href="<?php echo e(route('front.atom')); ?>" />
    <updated><?php echo e(optional($posts->first()?->updated_at ?? now())->toAtomString()); ?></updated>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <entry>
            <title><?php echo e($post->title); ?></title>
            <id><?php echo e(route('front.show', $post->slug)); ?></id>
            <link href="<?php echo e(route('front.show', $post->slug)); ?>" />
            <updated><?php echo e(optional($post->updated_at)->toAtomString()); ?></updated>
            <summary><?php echo e($post->excerpt); ?></summary>
        </entry>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</feed>

<?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/atom.blade.php ENDPATH**/ ?>