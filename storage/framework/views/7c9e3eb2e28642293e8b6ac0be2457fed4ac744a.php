<?php $__empty_1 = true; $__currentLoopData = $threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

    <div class="card">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="<?php echo e($thread->path()); ?>">
                            
                            <?php if(auth()->check() && $thread->hasUpdatesFor(auth()->user())): ?>
                            <strong><?php echo e($thread->title); ?></strong>
                            <?php else: ?>
                                <?php echo e($thread->title); ?>

                            <?php endif; ?>
                        </a>
                    </h4>
                    <h5>Posted by:  <a href="<?php echo e(route('profile', $thread->creator)); ?>"><?php echo e($thread->creator->name); ?></a></h5>
                </div>
                <a href="<?php echo e($thread->path()); ?>">
                    <?php echo e($thread->replies_count); ?> <?php echo e(str_plural('reply', $thread->replies_count)); ?>  
                </a>
            </div>
        </div>

        <div class="card-body">
            <article>
                <div class="body">
                    <?php echo e($thread->body); ?>

                </div>
            </article>
            <hr>
        </div>
        
        <div class="card-footer">
            
            
            <?php echo e($thread->visits); ?> visits
        </div>
        
    </div>
        <p class="mt-4"></p>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p>There are no relevant results at this time</p>
<?php endif; ?>