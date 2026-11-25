
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Academy ERP')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        (() => {
            const savedTheme = localStorage.getItem('academy-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (! savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors dark:bg-slate-950 dark:text-slate-100">

    <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">
                Academy<span class="text-indigo-600">ERP</span>
            </a>

            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">Sign in</a>
                @else
                    <a href="{{ route('account') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">Dashboard</a>
                @endguest

            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                Development
            </span>

            <button type="button" id="theme-toggle" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Toggle light and dark theme">
                <span id="theme-toggle-label">Dark mode</span>
            </button>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl px-6 py-10">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-5 dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-6 text-sm text-slate-500">
            Academy ERP — Education Management Platform
        </div>
    </footer>

    @livewireScripts
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleLabel = document.getElementById('theme-toggle-label');

        const syncThemeLabel = () => {
            themeToggleLabel.textContent = document.documentElement.classList.contains('dark') ? 'Light mode' : 'Dark mode';
        };

        syncThemeLabel();
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('academy-theme', isDark ? 'dark' : 'light');
            syncThemeLabel();
        });
    </script>
</body>
</html>
