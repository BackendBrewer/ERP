
@extends('layouts.app')

@section('title', 'My Account | Academy ERP')

@section('content')

<section class="max-w-2xl">

    <h1 class="text-3xl font-bold text-slate-900">
        My Account
    </h1>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">

        <p class="text-sm text-slate-500">Signed in as</p>

        <h2 class="mt-1 text-xl font-semibold">
            {{ auth()->user()->name }}
        </h2>

        <p class="mt-1 text-slate-600">
            {{ auth()->user()->email }}
        </p>

        <a href="{{ route('institutes.select') }}" class="mt-5 inline-flex rounded-lg bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
            Choose an institute
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf

            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-5 py-3 font-medium text-white hover:bg-slate-700"
            >
                Sign Out
            </button>
        </form>

    </div>

</section>

@endsection
