<?php $__env->startSection('header'); ?>
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">

    <!--
    <script>
        window.thread = <?= json_encode($thread); ?>
    </script>
-->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<thread-view :thread ="<?php echo e($thread); ?>" inline-template>


<div class="container">
    <div class="row">
        <div class="col-md-8">
                
                <?php echo $__env->make('threads._question', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <replies
                    @added="repliesCount++"
                    @removed="repliesCount--"
                    >
                </replies>
                
                


                

        </div>

        <div class="col-md-4">
            <div class="card">
            
                <div class="card-body">
                    <p>
                        This thread was published <?php echo e($thread->created_at->diffForHumans()); ?> by  <a href=""><?php echo e($thread->creator->name); ?></a> 
                        
                        and currently has <span v-text="repliesCount"></span> <?php echo e(str_plural('comment', $thread->replies_count )); ?>


                    </p>

                    <p>
                        
                        
                        
                        <subscribe-button :active = "<?php echo e(json_encode($thread->isSubscribedTo)); ?>" v-if="signedIn"></subscribe-button>


                        <button class="btn btn-default" 
                            v-if="authorize('isAdmin')" 
                            @click="toggleLock" 
                            v-text="locked ? 'Locked' : 'Lock'"
                            >
                        </button>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</thread-view>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>