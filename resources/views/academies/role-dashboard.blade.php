@extends('layouts.app')

@section('title', $roleName.' Dashboard | '.$institute->name)

@section('content')
@php
    $greeting = now()->hour < 12 ? 'Good morning' : (now()->hour < 18 ? 'Good afternoon' : 'Good evening');
@endphp

<div class="mx-auto max-w-7xl">
    <div class="flex flex-col gap-6 lg:flex-row">
        <aside class="w-full shrink-0 lg:w-64">
            <div class="rounded-2xl bg-slate-900 p-5 text-slate-100 shadow-lg lg:sticky lg:top-6">
                <a href="{{ route('workspace') }}" class="flex items-center gap-3">
                    <span class="grid size-11 place-items-center rounded-xl bg-indigo-500 font-bold text-white">{{ mb_strtoupper(mb_substr($institute->name, 0, 1)) }}</span>
                    <span class="min-w-0"><span class="block truncate font-semibold">{{ $institute->name }}</span><span class="mt-0.5 block text-xs text-slate-400">{{ $roleName }}</span></span>
                </a>

                <nav class="mt-7 space-y-1" aria-label="{{ $roleName }} navigation">
                    <a href="{{ route('workspace') }}" aria-current="page" class="flex items-center gap-3 rounded-lg bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white"><span>▦</span>Dashboard</a>

                    @if (auth()->user()->hasInstitutePermission('roles.view', $institute))
                        <a href="{{ route('roles.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><span>♙</span>Team & roles</a>
                        <a href="{{ route('roles.permissions') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><span>▦</span>Permission matrix</a>
                    @endif
                    @if (auth()->user()->hasInstitutePermission('staff.view', $institute))
                        <a href="{{ route('team.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><span>＋</span>Staff accounts</a>
                    @endif
                    @if (auth()->user()->hasInstitutePermission('institute.profile.view', $institute))
                        <a href="{{ route('institute.profile') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><span>⌂</span>Institute profile</a>
                    @endif
                    @if (auth()->user()->hasInstitutePermission('audit.view', $institute))
                        <a href="{{ route('audit.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><span>◷</span>Audit history</a>
                    @endif

                    @if ($roleSlug === 'teacher')
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>▤</span>My classes <span class="ml-auto text-[10px]">SOON</span></span>
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>✓</span>Attendance <span class="ml-auto text-[10px]">SOON</span></span>
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>☆</span>Examinations <span class="ml-auto text-[10px]">SOON</span></span>
                    @elseif ($roleSlug === 'coordinator')
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>▤</span>Academic groups <span class="ml-auto text-[10px]">SOON</span></span>
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>✓</span>Attendance oversight <span class="ml-auto text-[10px]">SOON</span></span>
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>☆</span>Exam schedule <span class="ml-auto text-[10px]">SOON</span></span>
                    @elseif ($roleSlug === 'receptionist')
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>＋</span>Admissions <span class="ml-auto text-[10px]">SOON</span></span>
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>♙</span>Student search <span class="ml-auto text-[10px]">SOON</span></span>
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-500"><span>▣</span>Fee receipts <span class="ml-auto text-[10px]">SOON</span></span>
                    @else
                        <span class="rounded-lg px-3 py-2.5 text-sm text-slate-400">Role dashboard not assigned</span>
                    @endif

                    <a href="{{ route('institutes.select') }}" class="mt-4 flex items-center gap-3 rounded-lg border-t border-slate-700 px-3 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><span>⇄</span>Switch institute</a>
                </nav>

                <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t border-slate-700 pt-3">
                    @csrf
                    <button class="w-full rounded-lg px-3 py-2.5 text-left text-sm text-slate-300 hover:bg-slate-800 hover:text-white">Sign out</button>
                </form>

                <div class="mt-6 rounded-xl bg-slate-800 p-3">
                    <p class="text-xs text-slate-400">Institute status</p>
                    <p class="mt-1 flex items-center gap-2 text-sm font-medium text-white"><span class="size-2 rounded-full bg-emerald-400"></span>{{ ucfirst($institute->status) }}</p>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">{{ now()->format('l, F j, Y') }}</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 dark:text-white">{{ $greeting }}, {{ auth()->user()->name }}</h1>
                    <p class="mt-1 text-slate-600">{{ $roleName }} workspace for {{ $institute->name }}.</p>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                    <span class="grid size-9 place-items-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span><span class="block text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</span><span class="block text-xs text-slate-500">{{ $roleName }}</span></span>
                </div>
            </div>

            <section class="mt-7 grid gap-4 lg:grid-cols-[1.6fr_0.9fr]">
                <div class="rounded-2xl bg-gradient-to-r from-indigo-800 via-indigo-700 to-sky-700 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-indigo-100">{{ $institute->slug }} · Institute workspace</p>
                <h2 class="mt-2 text-2xl font-bold">Your work, in one place</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">This dashboard is tailored to your {{ strtolower($roleName) }} role. Information appears here as the related institute modules are configured.</p>
                @if (auth()->user()->hasInstitutePermission('staff.view', $institute))
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('team.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-indigo-800 hover:bg-indigo-50">Manage staff</a>
                    </div>
                @endif
                @if (auth()->user()->hasInstitutePermission('institute.profile.view', $institute))
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('institute.profile') }}" class="rounded-lg border border-white/40 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">Update institute profile</a>
                    </div>
                @endif
                </div>

                <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">At a glance</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $roleName }} overview</p>
                    <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach (array_slice($dashboardCards, 0, 3) as $card)
                            <div class="flex items-center justify-between gap-4 py-3"><span class="text-xs text-slate-500">{{ $card['label'] }}</span><span class="text-right text-sm font-semibold text-slate-900 dark:text-white">{{ $card['value'] }}</span></div>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs leading-5 text-slate-500">Summaries are based on the information currently available to this workspace.</p>
                </aside>
            </section>

            <section class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Role-specific summary">
                @foreach ($dashboardCards as $card)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950">{{ $card['value'] }}</p>
                        <p class="mt-2 text-xs leading-5 text-slate-500">{{ $card['detail'] }}</p>
                    </article>
                @endforeach
            </section>

            <section class="mt-7 grid gap-5 xl:grid-cols-[1.4fr_1fr]">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div><h2 class="text-lg font-semibold text-slate-900">Today at a glance</h2><p class="mt-1 text-sm text-slate-500">Role-relevant updates will appear here when those modules are available.</p></div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ now()->format('D, M j') }}</span>
                    </div>
                    <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                        <span class="mx-auto grid size-11 place-items-center rounded-full bg-indigo-100 text-xl text-indigo-700">⌁</span>
                        <p class="mt-3 font-medium text-slate-800 dark:text-slate-100">Live activity is not available yet</p>
                        <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-500">Student, class, attendance and fee modules are not configured in this workspace. Their live updates will appear here when those modules are built.</p>
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Workspace setup</h2>
                    <p class="mt-1 text-sm text-slate-500">Available setup details for your institute.</p>
                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between gap-3"><span class="text-sm text-slate-600">Institute profile</span><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $institute->profile?->phone ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $institute->profile?->phone ? 'Contact added' : 'Contact details needed' }}</span></div>
                        <div class="flex items-center justify-between gap-3"><span class="text-sm text-slate-600">Your workspace role</span><span class="text-sm font-semibold text-slate-900">{{ $roleName }}</span></div>
                        <div class="flex items-center justify-between gap-3"><span class="text-sm text-slate-600">Academic modules</span><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">Planned</span></div>
                    </div>
                    @if (auth()->user()->hasInstitutePermission('institute.profile.view', $institute))
                        <a href="{{ route('institute.profile') }}" class="mt-6 inline-flex text-sm font-semibold text-indigo-700 hover:text-indigo-800">Review institute profile <span class="ml-1">→</span></a>
                    @endif
                </article>
            </section>

            <p class="mt-7 border-t border-slate-200 pt-5 text-xs text-slate-500">{{ $institute->name }} · Academy ERP</p>
        </main>
    </div>
</div>
@endsection
