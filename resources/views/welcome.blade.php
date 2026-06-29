<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 text-white antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-16">
            <p class="text-2xl sm:text-3xl font-semibold mb-12 text-center">
                this is my first internship project
            </p>
            <div class="flex gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg border border-white/10 px-4 py-2 hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('posts.index') }}" class="rounded-lg bg-white px-4 py-2 font-medium text-slate-950">Posts</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg border border-white/10 px-4 py-2 hover:bg-white/10">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-indigo-500 px-4 py-2 font-medium hover:bg-indigo-400">Sign up</a>
                @endauth
            </div>
        </div>
    </body>
</html>
