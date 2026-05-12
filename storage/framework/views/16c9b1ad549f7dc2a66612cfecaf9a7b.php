<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="<?php echo e($description ?? 'CMS Laravel libre, modulaire et auto-hebergeable.'); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($content)): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($content->meta, 'seo.noindex')): ?>
            <meta name="robots" content="noindex,nofollow">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($content->meta, 'seo.canonical_url')): ?>
            <link rel="canonical" href="<?php echo e(data_get($content->meta, 'seo.canonical_url')); ?>">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <link rel="manifest" href="/manifest.webmanifest">
    <title><?php echo e(isset($title) ? $title.' - '.config('app.name') : config('app.name')); ?></title>
    <style>
        :root { color-scheme: light dark; --bg: #f8fafc; --panel: #ffffff; --text: #0f172a; --muted: #64748b; --line: #d9e2ec; --brand: #0f766e; --accent: #b91c1c; }
        @media (prefers-color-scheme: dark) { :root { --bg: #0b1120; --panel: #111827; --text: #f8fafc; --muted: #94a3b8; --line: #263244; --brand: #2dd4bf; --accent: #fca5a5; } }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--text); font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; line-height: 1.6; }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; height: auto; border-radius: 6px; }
        .shell { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        .topbar { border-bottom: 1px solid var(--line); background: color-mix(in srgb, var(--panel) 86%, transparent); position: sticky; top: 0; backdrop-filter: blur(14px); z-index: 10; }
        .nav { display: flex; min-height: 64px; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { font-weight: 800; letter-spacing: 0; }
        .navlinks { display: flex; align-items: center; gap: 14px; color: var(--muted); font-size: 14px; }
        .button, button { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; border: 1px solid var(--line); border-radius: 6px; padding: 8px 14px; background: var(--panel); color: var(--text); font-weight: 650; cursor: pointer; }
        .button.primary, button.primary { background: var(--brand); border-color: var(--brand); color: #fff; }
        .button.danger, button.danger { color: var(--accent); }
        .hero { padding: 72px 0 34px; }
        .hero h1 { margin: 0; max-width: 760px; font-size: clamp(42px, 7vw, 78px); line-height: .95; letter-spacing: 0; }
        .hero p { max-width: 680px; color: var(--muted); font-size: 19px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .card { background: var(--panel); border: 1px solid var(--line); border-radius: 8px; padding: 18px; }
        .card h2, .card h3 { margin-top: 0; line-height: 1.2; }
        .muted { color: var(--muted); }
        .content { padding: 38px 0 72px; }
        .prose { max-width: 760px; }
        .prose h2 { margin-top: 32px; font-size: 30px; line-height: 1.15; }
        .admin-layout { display: grid; grid-template-columns: 220px 1fr; min-height: calc(100vh - 64px); }
        .sidebar { border-right: 1px solid var(--line); padding: 20px; background: var(--panel); }
        .sidebar a { display: block; padding: 9px 0; color: var(--muted); }
        .admin-main { padding: 24px; overflow: auto; }
        table { width: 100%; border-collapse: collapse; background: var(--panel); border: 1px solid var(--line); border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        input, select, textarea { width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 10px 12px; background: var(--panel); color: var(--text); font: inherit; }
        textarea { min-height: 320px; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
        label { display: grid; gap: 6px; margin-bottom: 14px; font-weight: 650; }
        .form-actions { display: flex; gap: 10px; align-items: center; }
        .notice { margin: 0 0 16px; padding: 10px 12px; border: 1px solid var(--line); border-radius: 6px; background: var(--panel); }
        @media (max-width: 760px) { .admin-layout { grid-template-columns: 1fr; } .sidebar { border-right: 0; border-bottom: 1px solid var(--line); } .nav { align-items: flex-start; flex-direction: column; padding: 14px 0; } }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="shell nav">
            <a href="<?php echo e(route('front.home')); ?>" class="brand"><?php echo e(config('app.name')); ?></a>
            <nav class="navlinks">
                <a href="<?php echo e(route('front.blog')); ?>">Blog</a>
                <a href="<?php echo e(route('front.search')); ?>">Recherche</a>
                <a href="<?php echo e(route('front.rss')); ?>">RSS</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a>
                    <form method="post" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit">Sortir</button>
                    </form>
                <?php else: ?>
                    <a class="button" href="<?php echo e(route('login')); ?>">Connexion</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        </div>
    </header>

    <?php echo $__env->yieldContent('body'); ?>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js').catch(() => {});
        }
    </script>
</body>
</html>
<?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/layouts/app.blade.php ENDPATH**/ ?>