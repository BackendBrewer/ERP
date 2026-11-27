<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title') | {{ config('app.name', 'Academy ERP') }}</title>
    <link rel="stylesheet" href="{{ asset('css/erp-theme.css') }}">
    <script src="{{ asset('js/erp-app.js') }}" defer></script>
    @stack('styles')
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    @auth
        @php
            $platform = auth()->user()->can('access-super-admin');
            $home = $platform ? 'super-admin.dashboard' : 'institute.dashboard';
            $navigation = $platform
                ? [
                    ['Workspace', [
                        ['Overview', 'super-admin.dashboard', 'super-admin.dashboard', 'home'],
                    ]],
                    ['Management', [
                        ['Academies', 'super-admin.academies.index', 'super-admin.academies.*', 'building'],
                    ]],
                ]
                : [
                    ['Workspace', [
                        ['Overview', 'institute.dashboard', 'institute.dashboard', 'home'],
                    ]],
                    ['Academic setup', [
                        ['Classes & Sections', 'institute.academic-setup.index', 'institute.academic-setup.*', 'grid'],
                        ['Subjects', 'institute.subjects.index', 'institute.subjects.*', 'book'],
                        ['Subject Groups', 'institute.subject-groups.index', 'institute.subject-groups.*', 'layers'],
                    ]],
                    ['Admissions', [
                        ['Admission Inquiries', 'institute.admission-inquiries.index', 'institute.admission-inquiries.*', 'school'],
                        ['Students', 'institute.students.index', 'institute.students.*', 'school'],
                    ]],
                ];
        @endphp

        <div class="sidebar-backdrop" data-sidebar-backdrop aria-hidden="true"></div>
        <div class="app-shell">
            <aside class="sidebar" data-sidebar aria-label="Application sidebar">
                <a class="brand" href="{{ route($home) }}" aria-label="Academy ERP home">
                    <span class="brand-mark" aria-hidden="true">A</span>
                    <span class="brand-label">Academy ERP</span>
                </a>
                <div class="workspace-card">
                    <span class="workspace-avatar" aria-hidden="true"><x-icon name="school" /></span>
                    <span class="workspace-copy">
                        <strong>{{ $platform ? 'Platform workspace' : 'Institute workspace' }}</strong>
                        <span>{{ $platform ? 'Super Admin' : 'Institute Admin' }}</span>
                    </span>
                </div>
                <nav id="main-navigation" class="main-nav" aria-label="Main navigation">
                    @foreach ($navigation as [$group, $items])
                        <p class="nav-caption">{{ $group }}</p>
                        @foreach ($items as [$label, $target, $pattern, $icon])
                            @php($active = request()->routeIs($pattern))
                            <a @class(['nav-link', 'active' => $active]) href="{{ route($target) }}"
                               aria-label="{{ $label }}" title="{{ $label }}"
                               @if ($active) aria-current="{{ request()->routeIs($target) ? 'page' : 'true' }}" @endif>
                                <span class="nav-icon"><x-icon :name="$icon" /></span>
                                <span class="nav-label">{{ $label }}</span>
                            </a>
                        @endforeach
                    @endforeach
                </nav>
                <div class="sidebar-bottom">
                    <div class="sidebar-note"><span class="accent-dot" aria-hidden="true"></span><span>Keep your academy workspace organized in one place.</span></div>
                    <div class="sidebar-foot">Academy ERP · <span data-current-year></span></div>
                </div>
            </aside>

            <div class="app-body">
                <header class="topbar">
                    <div class="topbar-left">
                        <button class="icon-button mobile-menu-button" type="button" data-sidebar-toggle aria-label="Open navigation" aria-controls="main-navigation" aria-expanded="false"><x-icon name="menu" /></button>
                        <button class="icon-button desktop-collapse-button" type="button" data-sidebar-collapse aria-label="Collapse sidebar" aria-pressed="false" title="Collapse sidebar"><x-icon name="menu" /></button>
                        <span class="topbar-label">{{ $platform ? 'Platform workspace' : 'Institute workspace' }}</span>
                    </div>
                    <label class="global-search" aria-label="Search this page">
                        <x-icon name="search" />
                        <input type="search" data-global-search placeholder="Search this page…" autocomplete="off">
                    </label>
                    <div class="topbar-actions">
                        <div class="dropdown-anchor">
                            <button class="icon-button" type="button" data-notification-toggle aria-label="Notifications" aria-expanded="false" aria-controls="notification-panel"><x-icon name="bell" /></button>
                            <div class="notification-panel" id="notification-panel" data-notification-panel hidden>
                                <strong>You’re all caught up</strong>
                                <p>New workspace updates will appear here.</p>
                            </div>
                        </div>
                        <button class="icon-button" type="button" data-theme-toggle aria-label="Switch to dark theme" aria-pressed="false" title="Toggle color theme"><x-icon name="moon" /></button>
                        <details class="account-menu">
                            <summary class="account" aria-label="Account menu">
                                <span class="avatar" aria-hidden="true">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                                <span class="account-name">{{ auth()->user()->name }}</span>
                            </summary>
                            <div class="account-dropdown">
                                <span class="account-dropdown-name">{{ auth()->user()->name }}</span>
                                <span class="account-dropdown-role">{{ $platform ? 'Super Admin' : 'Institute Admin' }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="button button-quiet button-block">Sign out</button>
                                </form>
                            </div>
                        </details>
                    </div>
                </header>
                <main id="main-content" class="main-content animate-in" tabindex="-1">
                    @yield('content')
                </main>
                <footer class="app-footer">
                    <span>© <span data-current-year></span> Academy ERP</span>
                    <span>Made for better learning communities</span>
                </footer>
            </div>
        </div>
    @else
        <main id="main-content" class="guest-content" tabindex="-1">
            @yield('content')
        </main>
    @endauth
    @stack('scripts')
</body>
</html>
