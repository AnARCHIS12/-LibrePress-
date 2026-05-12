<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
    <channel>
        <title><?php echo e(config('app.name')); ?></title>
        <link><?php echo e(url('/')); ?></link>
        <description>Flux RSS <?php echo e(config('app.name')); ?></description>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <item>
                <title><?php echo e($post->title); ?></title>
                <link><?php echo e(route('front.show', $post->slug)); ?></link>
                <guid><?php echo e(route('front.show', $post->slug)); ?></guid>
                <description><?php echo e($post->excerpt); ?></description>
                <pubDate><?php echo e(optional($post->published_at)->toRfc2822String()); ?></pubDate>
            </item>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </channel>
</rss>

<?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/rss.blade.php ENDPATH**/ ?>