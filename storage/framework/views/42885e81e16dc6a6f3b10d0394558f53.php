<?php $__env->startSection('body'); ?>
    <main class="shell content">
        <div class="card" style="max-width: 460px">
            <h1>Connexion</h1>
            <form method="post" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <label>
                    Email
                    <input name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus>
                </label>
                <label>
                    Mot de passe
                    <input name="password" type="password" required>
                </label>
                <label style="display: flex; grid-template-columns: auto 1fr; align-items: center">
                    <input name="remember" type="checkbox" value="1" style="width: auto">
                    Se souvenir de moi
                </label>
                <button class="primary" type="submit">Entrer</button>
            </form>
        </div>
    </main>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/anar/Bureau/testcodetrasl/resources/views/auth/login.blade.php ENDPATH**/ ?>