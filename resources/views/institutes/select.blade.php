
@extends('layouts.app')

@section('title', 'Select Institute | Academy ERP')

@section('content')

<section class="mx-auto max-w-3xl py-8">

    <h1 class="text-3xl font-bold">
        Select Your Institute
    </h1>

    <p class="mt-3 text-slate-600">
        Choose the institute you want to manage.
    </p>

    @if ($institutes->isEmpty())

        <div class="mt-8 rounded-xl border border-amber-200 bg-amber-50 p-6">
            <h2 class="font-semibold text-amber-900">
                No active institute membership
            </h2>

            <p class="mt-2 text-sm text-amber-800">
                Your account is not associated with an active institute.
                Contact your platform administrator.
            </p>
        </div>

    @else

        <div class="mt-8 grid gap-4 sm:grid-cols-2">

            @foreach ($institutes as $institute)

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-semibold">
                        {{ $institute->name }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $institute->slug }}
                    </p>

                    <form method="POST"
                          action="{{ route('institutes.select.store') }}"
                          class="mt-6">

                        @csrf

                        <input type="hidden"
                               name="institute_id"
                               value="{{ $institute->id }}">

                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-3 font-medium text-white hover:bg-indigo-700">
                            Continue
                        </button>

                    </form>
                </div>

            @endforeach

        </div>

    @endif

    <form method="POST" action="{{ route('logout') }}" class="mt-8">
        @csrf

        <button type="submit" class="text-sm text-slate-600 underline">
            Sign out
        </button>
    </form>

</section>

@endsection
