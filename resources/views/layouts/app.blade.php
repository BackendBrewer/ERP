
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Academy ERP')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">
                Academy<span class="text-indigo-600">ERP</span>
            </a>

            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                Development
            </span>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl px-6 py-10">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-5">
        <div class="mx-auto max-w-7xl px-6 text-sm text-slate-500">
            Academy ERP — Education Management Platform
        </div>
    </footer>

    @livewireScripts
</body>
</html>