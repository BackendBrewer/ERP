@extends('layouts.app')

@section('title', $institute->name.' | Academy ERP')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex flex-col gap-8 lg:flex-row">
        <aside class="w-full shrink-0 lg:w-64">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-6">
                <div class="flex items-center gap-3">
                    <div class="grid size-11 place-items-center rounded-xl bg-indigo-600 text-lg font-bold text-white">{{ mb_strtoupper(mb_substr($institute->name, 0, 1)) }}</div>
                    <div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $institute->name }}</p><p class="text-xs text-slate-500">Institute workspace</p></div>
                </div>
                <nav class="mt-7 space-y-1" aria-label="Institute navigation">
                    <a href="{{ route('workspace') }}" class="block rounded-lg bg-indigo-50 px-3 py-2.5 text-sm font-semibold text-indigo-700">Dashboard</a>
                    <a href="{{ route('institute.profile') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Institute profile</a>
                    <a href="{{ route('roles.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Roles</a>
                    <a href="{{ route('institutes.select') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Switch institute</a>
                </nav>
                <form method="POST" action="{{ route('logout') }}" class="mt-6 border-t border-slate-100 pt-5">
                    @csrf
                    <button class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 hover:bg-slate-50">Sign out</button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div><p class="text-sm font-medium text-indigo-600">Academy workspace</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Dashboard</h1><p class="mt-2 text-slate-600">Welcome back. Your institute workspace is ready.</p></div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700"><span class="size-2 rounded-full bg-emerald-500"></span>{{ ucfirst($institute->status) }}</span>
            </div>

            <section class="mt-7 rounded-2xl bg-gradient-to-br from-indigo-700 to-violet-700 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-indigo-100">Institute overview</p>
                <h2 class="mt-2 text-2xl font-bold">{{ $institute->name }}</h2>
                <p class="mt-2 text-sm text-indigo-100">{{ $institute->profile?->city ?: 'Add your city in the institute profile' }} <span class="px-1">·</span> {{ $institute->slug }}</p>
                <a href="{{ route('institute.profile') }}" class="mt-5 inline-flex rounded-lg bg-white/15 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/25">View institute profile</a>
            </section>

            <section class="mt-8">
                <div class="flex items-end justify-between"><div><h2 class="text-lg font-semibold text-slate-900">Operations</h2><p class="mt-1 text-sm text-slate-500">Your academy tools will appear here as milestones are completed.</p></div></div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ([['Students & admissions', 'Student profiles and admission workflows', 'M04–M05'], ['Academics', 'Sessions, classes, subjects and enrollment', 'M06–M07'], ['Fees & accounting', 'Billing, collections and full accounting', 'M12–M15'], ['Attendance & exams', 'Daily attendance, assessments and results', 'M16–M17'], ['Staff & communication', 'Staff operations and guardian notifications', 'M19–M20'], ['Reports & exports', 'Operational reports and document exports', 'M21']] as [$title, $description, $milestone])
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-start justify-between gap-3"><h3 class="font-semibold text-slate-900">{{ $title }}</h3><span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500">{{ $milestone }}</span></div><p class="mt-2 text-sm leading-6 text-slate-600">{{ $description }}</p><p class="mt-4 text-xs font-medium uppercase tracking-wide text-slate-400">Planned</p></article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</div>
@endsection
