<?xml version="1.0" encoding="UTF-8" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo e(url('/')); ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <url>
            <loc><?php echo e(route('front.show', $content->slug)); ?></loc>
            <lastmod><?php echo e($content->updated_at->toDateString()); ?></lastmod>
            <changefreq><?php echo e($content->type === 'post' ? 'weekly' : 'monthly'); ?></changefreq>
            <priority><?php echo e($content->type === 'post' ? '0.8' : '0.7'); ?></priority>
        </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</urlset>

<?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/front/sitemap.blade.php ENDPATH**/ ?>