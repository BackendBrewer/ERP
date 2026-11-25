@extends('layouts.app')

@section('title', 'Set Up Your Institute | Academy ERP')

@section('content')
<section class="mx-auto max-w-xl">
    <h1 class="text-3xl font-bold text-slate-900">Set up your institute</h1>
    <p class="mt-2 text-slate-600">Create your institute workspace and its first administrator account.</p>

    <form method="POST" action="{{ route('onboarding.store') }}" class="mt-8 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label for="institute_name" class="block text-sm font-medium">Institute name</label>
            <input id="institute_name" name="institute_name" value="{{ old('institute_name') }}" required maxlength="255" class="mt-1 w-full rounded-lg border-slate-300">
            @error('institute_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

            <div>
                <label for="name" class="block text-sm font-medium">Your name</label>
                <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" class="mt-1 w-full rounded-lg border-slate-300">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1 w-full rounded-lg border-slate-300">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="mt-1 w-full rounded-lg border-slate-300">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror n            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 w-full rounded-lg border-slate-300">
            </div>
        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-3 font-semibold text-white hover:bg-indigo-700">Create institute workspace</button>
    </form>
</section>
@endsection
