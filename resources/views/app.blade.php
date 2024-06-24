<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Todo</title>
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id=app>
        <nav>
            <router-link to="/">Notes</router-link>
            <router-link to="/login">Log In</router-link>
            <router-link to="/register">Sign Up</router-link>

        </nav>
        <main>
            <router-view></router-view>
        </main>
    </div>
</body>

</html>
