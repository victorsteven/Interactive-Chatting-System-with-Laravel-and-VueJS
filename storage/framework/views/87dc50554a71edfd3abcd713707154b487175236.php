<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

    <script>
        window.App = <?php echo json_encode([
            'csrftoken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check(),
        ]); ?>

    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">


    <style>
        .level {
            display: flex;
            align-items: center;
        }
        .flex {
            flex: 1;
        }
        
        [v-cloak] {
            display: none;
        }
    </style>

    <?php echo $__env->yieldContent('header'); ?>

</head>
<body style="padding-bottom: 100px;">
    <div id="app" v-cloak>
        
        <?php echo $__env->make('layouts.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div id="vue1">
            <greeting></greeting>
        </div>

        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>

            <flash message="<?php echo e(session('flash')); ?>"></flash>
        </main>
    </div>

    <?php echo $__env->yieldContent('scripts'); ?>

<script src="<?php echo e(asset('js/notice.js')); ?>"></script>

</body>
</html>
