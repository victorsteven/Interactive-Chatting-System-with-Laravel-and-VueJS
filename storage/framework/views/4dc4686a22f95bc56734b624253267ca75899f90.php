<?php $__env->startSection('header'); ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Thread</div>
                
                <div class="card-body">
                    <form action="/threads" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="form-group">
                            <select name="channel_id" id="channel_id" class="form-control">
                                <option value="">Select...</option>
                                <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($channel->id); ?>" <?php echo e(old('channel_id') == $channel->id ? 'selected' : ''); ?>><?php echo e($channel->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Title</label>
                            <input type="text" name="title" value="<?php echo e(old('title')); ?>"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Body</label>
                            <textarea name="body" id="" rows="8" class="form-control"><?php echo e(old('body')); ?></textarea>
                        </div>

                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6LdE0mkUAAAAAOB-iFL2gWpuUE6XT8Ct5bFxrrIc"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        

                        <?php if(count($errors)): ?>
                        <ul class="alert alert-danger">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endif; ?>

                    </form>

                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>