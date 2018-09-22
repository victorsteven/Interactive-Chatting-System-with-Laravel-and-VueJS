<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        window.App = {!! json_encode([
            'csrftoken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check(),
        ]) !!}
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


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

    @yield('header')

</head>
<body style="padding-bottom: 100px;">
    <div id="app" v-cloak>
        
        @include('layouts.nav')

        <main class="py-4">
            @yield('content')

            <flash message="{{ session('flash') }}"></flash>
        </main>
    </div>

    @yield('scripts')
</body>
</html>
