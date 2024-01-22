<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title : 'The Popcorn Post' }}</title>
        <link rel="icon" type="image/x-icon" href="/images/logo.png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        @vite('resources/css/auth.css')
        <script type="text/javascript">
        </script>
        <script type="text/javascript" src={{ url('js/auth.js') }} defer>
        </script>
    </head>
    <body class="h-screen bg-background font-main flex flex-col overflow-y-scroll items-center justify-between">
        <div class="flex flex-col items-center w-full">
            <h1 class="row-start-1 row-span-2 px-2 my-14 sm:my-24 font-extrabold text-5xl sm:text-6xl lg:text-8xl text-black self-center text-center font-title">
                <a href="{{ url('/') }}">The Popcorn Post</a>
            </h1>
            @yield('authentication')
        </div>
        <p class="text-center mt-10">
            Go <a class="text-red font-semibold text-center" href="{{ route('home') }}">Home</a>
        </p>
    </body>
</html>