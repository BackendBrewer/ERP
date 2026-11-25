@extends('layouts.app')

@section('title', 'Platform Dashboard | Academy ERP')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex flex-col gap-8 lg:flex-row">
        <aside class="w-full shrink-0 lg:w-64">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-6">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600">Platform</p>
                <p class="mt-2 text-lg font-bold">Super Admin</p>
                <nav class="mt-6 space-y-1" aria-label="Platform navigation">
                    <a href="{{ route('platform.dashboard') }}" class="block rounded-lg bg-indigo-50 px-3 py-2.5 text-sm font-semibold text-indigo-700">Overview</a>
                    <a href="{{ route('onboarding.create') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Create an institute</a>
                    <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Public site</a>
                </nav>
                <form method="POST" action="{{ route('logout') }}" class="mt-6 border-t border-slate-100 pt-5">
                    @csrf
                    <button class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 hover:bg-slate-50">Sign out</button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="mb-7">
                <p class="text-sm font-medium text-indigo-600">Platform administration</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Dashboard</h1>
                <p class="mt-2 text-slate-600">Institute account overview. Student and financial records remain within each institute workspace.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <section class="grid gap-4 sm:grid-cols-3" aria-label="Institute totals">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Total institutes</p><p class="mt-2 text-3xl font-bold">{{ number_format($instituteCount) }}</p></article>
                <article class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5"><p class="text-sm text-emerald-800">Active institutes</p><p class="mt-2 text-3xl font-bold text-emerald-950">{{ number_format($activeInstituteCount) }}</p></article>
                <article class="rounded-2xl border border-amber-200 bg-amber-50 p-5"><p class="text-sm text-amber-800">Suspended institutes</p><p class="mt-2 text-3xl font-bold text-amber-950">{{ number_format($suspendedInstituteCount) }}</p></article>
            </section>

            <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-semibold text-slate-900">Recently created institutes</h2><p class="mt-1 text-sm text-slate-500">Account-level information only.</p></div>
                @if ($recentInstitutes->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-slate-500">No institutes have been created yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Institute</th><th class="px-5 py-3">Members</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Created</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($recentInstitutes as $institute)
                                    <tr><td class="px-5 py-4"><p class="font-medium text-slate-900">{{ $institute->name }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $institute->slug }}</p></td><td class="px-5 py-4 text-slate-600">{{ $institute->users_count }}</td><td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $institute->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($institute->status) }}</span></td><td class="px-5 py-4 text-slate-600">{{ $institute->created_at->format('M j, Y') }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </main>
    </div>
</div>
@endsection
