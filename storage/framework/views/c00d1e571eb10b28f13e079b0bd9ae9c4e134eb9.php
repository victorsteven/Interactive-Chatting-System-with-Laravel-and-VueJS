

<div class="card" v-if="editing" v-cloak>
<div class="card-header">
    <div class="level">
        <span class="flex">
        
        <input type="text" name="title" id=""  class="form-control" v-model ="form.title">
        </span>
    </div>
</div>
<div class="card-body">
    
    <textarea name="body" id=""  class="form-control" rows="8" v-model="form.body"></textarea>
</div>

<div class="card-footer">
    <div class="level">
    <button class = "btn btn-sm btn-default" @click="editing = true" v-show="! editing">Edit</button>
    <button class = "btn btn-sm btn-primary" @click="updateThread">Update</button>
    <button class = "btn btn-sm btn-link mr-2" @click="resetForm">Cancel</button>

    <span class="ml-auto">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $thread)): ?>
    <form action="<?php echo e($thread->path()); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo e(method_field('DELETE')); ?>

        <button class="btn btn-link" type="submit">Delete Thread</button>
    </form>
    <?php endif; ?>
</span>
</div>
</div>
</div>



<div class="card" v-else>
<div class="card-header">
    <div class="level">
        <span class="flex">
        <img src="<?php echo e(asset($thread->creator->avatar_path)); ?>" alt="<?php echo e($thread->creator->name); ?>" width="25" height="25" class="mr-1">

        <a href="/profiles/<?php echo e($thread->creator->name); ?>"><?php echo e($thread->creator->name); ?></a> 
            posted:   <span v-text="title"></span>
        </span>

        
    </div>
    
</div>
<div class="card-body" v-text="body"></div>

<div class="card-footer" v-show="authorize('owns', thread)">
    <button class = "btn btn-sm btn-default" @click="editing = true">Edit</button>
</div>
</div>
