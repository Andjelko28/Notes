<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Todo</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id='app'>
        <nav class="d-flex justify-content-between m-2 fs-1">
            <router-link to="/" class="deco-none">Notes</router-link>
            <div>
                @auth
                    <router-link class="deco-none btn btn-primary">Log Out</router-link>
                @endauth
                @guest
                    <router-link to="/login" class="deco-none m-2 btn btn-primary">Log
                        In</router-link>
                    <router-link to="/register" class="deco-none btn btn-primary">Sign
                        Up</router-link>
                @endguest
            </div>
        </nav>
        <main>
            <router-view></router-view>
        </main>
    </div>


    <script>
        window.isAuth = @json(auth()->check());
    </script>
</body>

</html>
