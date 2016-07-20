<?php $__env->startSection('content'); ?>
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" role="form" method="POST" action="<?php echo e(url('/login')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="sign-avatar">
                        <img src="<?php echo e(asset('images/avatar-sign.png')); ?>">
                    </div>

                    <header class="sign-title">Sign In</header>


                    <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">

                        <input type="text" class="form-control" name="email" placeholder="Enter Email"
                               value="<?php echo e(old('email')); ?>">


                    </div>
                    <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                        <input type="password" class="form-control" name="password" placeholder="Password">

                    </div>
                    <div class="form-group">
                        <div class="checkbox float-left">
                            <input type="checkbox" name="remember" id="signed-in"/>
                            <label for="signed-in">Keep me signed in</label>
                        </div>
                        <div class="float-right reset">
                            <a href="<?php echo e(url('/password/reset')); ?>">Reset Password</a>
                        </div>
                    </div>
                    <?php if($errors->has('email')): ?>
                        <span class="help-block">
                                  <strong><?php echo e($errors->first('email')); ?></strong>
                            </span>
                    <?php endif; ?>
                    <?php if($errors->has('password')): ?>
                        <span class="help-block">
                                 <strong><?php echo e($errors->first('password')); ?></strong>
                            </span>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-rounded">Sign in</button>
                    <p class="sign-note">New to our website? <a href="<?php echo e(url('/register')); ?>">Sign up</a></p>
                </form>
            </div>
        </div>
    </div><!--.page-center-->
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.auth', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>