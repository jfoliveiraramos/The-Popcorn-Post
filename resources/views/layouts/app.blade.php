<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : 'The Popcorn Post' }}</title>
    <link rel="icon" type="image/x-icon" href="/images/logo.png">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @auth
        <script>
            const userID = {{ auth()->user()->id }};
            const username = '{{ auth()->user()->username }}';
            const pusherAPIKey = '{{ env('PUSHER_APP_KEY') }}';
            const pusherCluster = '{{ env('PUSHER_APP_CLUSTER') }}';
            const csrfToken = '{{ csrf_token() }}';
        </script>
    @endauth
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
    <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
    <script type="text/javascript" src={{ url('js/search.js') }} defer></script>
</head>

<body class="font-main bg-background">
    @hasSection('top-nav')
        <nav id="top-nav" class="bg-white shadow-[0_0_10px_1px_rgba(0,0,0,0.2)]">
            @yield('top-nav')
        </nav>
    @endif
    @hasSection('left-aside')
        <aside id="left-aside hidden">
            @yield('left-aside')
        </aside>
    @endif
    @hasSection('right-aside')
        <aside id="right-aside" class="hidden md:block px-10">
            @yield('right-aside')
        </aside>
    @endif
    <main class="col-span-3 md:col-span-2 md:ml-10 xl:col-start-2 xl:col-span-1 xl:ml-0 overflow-visible">
        <section id="content" class="overflow-visible px-6">
            @hasSection('header')
                <header id="header" class="mt-10 mb-2">
                    @yield('header')
                </header>
            @endif
            @yield('content')
        </section>
    </main>
    @yield('floating-button')

    @if (session('success'))
        <div id="snackbar" class="bg-green flex flex-row items-center justify-center">
            <i class="bi bi-check-circle text-xl mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->has('snackbar'))
        <div id="snackbar" class="bg-red flex flex-row items-center justify-center">
            <i class="bi bi-x-circle text-xl mr-2"></i>
            {{ $errors->first('snackbar') }}
        </div>
    @endif
</body>

</html>
