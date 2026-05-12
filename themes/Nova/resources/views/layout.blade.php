<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-950 antialiased dark:bg-slate-950 dark:text-white">
    <header class="border-b border-slate-200 dark:border-slate-800">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="/" class="font-semibold">{{ config('app.name') }}</a>
            <x-public.navigation />
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 py-8 text-sm text-slate-500 dark:border-slate-800">
        <div class="mx-auto max-w-6xl px-4">
            &copy; {{ now()->year }} {{ config('app.name') }}
        </div>
    </footer>
</body>
</html>

