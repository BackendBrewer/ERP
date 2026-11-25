
@extends('layouts.app')

@section('title', 'Workspace | Academy ERP')

@section('content')

<section class="max-w-4xl">

    <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">
        Institute Workspace
    </p>

    <h1 class="mt-3 text-3xl font-bold">
        {{ $institute->name }}
    </h1>

    <p class="mt-3 text-slate-600">
        You are accessing this workspace through an authorized
        institute membership.
    </p>

    <div class="mt-8 rounded-xl border border-slate-200 bg-white p-6">

        <h2 class="font-semibold">Current Institute</h2>

        <p class="mt-2 text-sm text-slate-600">
            {{ $institute->name }}
        </p>

        <p class="mt-1 text-sm text-slate-500">
            {{ $institute->slug }}
        </p>

        <div class="mt-6 flex flex-wrap gap-4">

            <a href="{{ route('institutes.select') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium">
                Switch Institute
            </a>

            <a href="{{ route('institute.profile') }}"
                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium">
                Institute Profile
            </a>


            <a href="{{ route('roles.index') }}"
            class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                Roles & Permissions
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">
                    Sign Out
                </button>
            </form>

        </div>

    </div>

</section>

@endsection
