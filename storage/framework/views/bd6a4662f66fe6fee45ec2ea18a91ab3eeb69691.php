<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php echo $__env->make('threads._list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            
            
            

            <?php echo e($threads->render()); ?>


        </div>

        <div class="col-md-4">
            <?php if(count($trending)): ?>
            <div class="card">
                <div class="card-header">
                    Trending Threads
                </div>

                <div class="card-body">
                    <ul class="list-group">
                    <?php $__currentLoopData = $trending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item">
                        <a href="<?php echo e(url($thread->path)); ?>"><?php echo e($thread->title); ?></a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>