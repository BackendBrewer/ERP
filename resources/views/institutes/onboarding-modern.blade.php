@extends('layouts.app')

@section('title', 'Create Institute | Academy ERP')

@section('content')
<div class="mx-auto max-w-7xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl dark:border-slate-800 dark:bg-slate-900">
    <div class="grid min-h-[760px] lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="flex flex-col justify-between bg-slate-950 p-7 text-white sm:p-9">
            <div>
                <a href="{{ route('platform.dashboard') }}" class="flex items-center gap-3">
                    <span class="grid size-12 place-items-center rounded-2xl border border-white/20 bg-white/10 text-xl font-bold">A</span>
                    <span><span class="block text-sm font-semibold tracking-wide">ACADEMY ERP</span><span class="mt-0.5 block text-xs text-slate-400">Platform workspace</span></span>
                </a>

                <div class="mt-14">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-300">Institute onboarding</p>
                    <h1 class="mt-4 text-3xl font-bold leading-tight">Set up a new academy</h1>
                    <p class="mt-4 text-sm leading-6 text-slate-300">Create the institute profile and its first administrator login in one guided step.</p>
                </div>

                <ol class="mt-10 space-y-5">
                    <li class="flex items-start gap-3"><span class="grid size-7 shrink-0 place-items-center rounded-full bg-indigo-500 text-xs font-bold">1</span><span><span class="block text-sm font-semibold">Institute profile</span><span class="mt-1 block text-xs leading-5 text-slate-400">Academy name and official contact information</span></span></li>
                    <li class="flex items-start gap-3"><span class="grid size-7 shrink-0 place-items-center rounded-full border border-slate-600 text-xs font-bold text-slate-300">2</span><span><span class="block text-sm font-semibold">Administrator account</span><span class="mt-1 block text-xs leading-5 text-slate-400">Separate sign-in credentials for the institute admin</span></span></li>
                </ol>
            </div>

            <div class="mt-12 rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm font-semibold">Account details stay separate</p>
                <p class="mt-2 text-xs leading-5 text-slate-300">The institute email is public contact information. Only the administrator account has a password.</p>
            </div>
        </aside>

        <main class="bg-slate-50 p-5 dark:bg-slate-950 sm:p-8 lg:p-10">
            <div class="mx-auto max-w-4xl">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div><p class="text-sm font-medium text-indigo-600">New institute</p><h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 dark:text-white">Institute and admin details</h2><p class="mt-2 text-sm text-slate-600">Institute contact information and administrator login are stored separately.</p></div>
                    <a href="{{ route('platform.dashboard') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">Back to dashboard</a>
                </div>

                <form method="POST" action="{{ route('onboarding.store') }}" class="mt-8 space-y-6">
                    @csrf

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-7">
                        <div class="mb-6 flex items-start gap-4">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-indigo-50 text-lg text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">⌂</span>
                            <div><h3 class="font-semibold text-slate-900 dark:text-white">Institute profile</h3><p class="mt-1 text-sm text-slate-500">These details describe the academy and can be shared with families.</p></div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="institute_name" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Institute name <span class="text-rose-500">*</span></label>
                                <input id="institute_name" name="institute_name" value="{{ old('institute_name') }}" required maxlength="255" placeholder="e.g. Bright Future Academy" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('institute_name')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="institute_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Primary phone</label>
                                <input id="institute_phone" name="institute_phone" type="tel" value="{{ old('institute_phone') }}" maxlength="30" placeholder="+92 300 1234567" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('institute_phone')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="institute_alternate_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Alternate phone</label>
                                <input id="institute_alternate_phone" name="institute_alternate_phone" type="tel" value="{{ old('institute_alternate_phone') }}" maxlength="30" placeholder="Optional contact number" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('institute_alternate_phone')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="institute_email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Institute email</label>
                                <input id="institute_email" name="institute_email" type="email" value="{{ old('institute_email') }}" maxlength="255" placeholder="info@academy.com" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('institute_email')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="institute_website" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Website</label>
                                <input id="institute_website" name="institute_website" type="url" value="{{ old('institute_website') }}" maxlength="255" placeholder="https://academy.com" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('institute_website')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="institute_city" class="block text-sm font-medium text-slate-700 dark:text-slate-200">City</label>
                                <input id="institute_city" name="institute_city" value="{{ old('institute_city') }}" maxlength="120" placeholder="Lahore" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('institute_city')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="institute_address" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Address</label>
                                <textarea id="institute_address" name="institute_address" rows="2" maxlength="255" placeholder="Street, area, and any useful directions" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('institute_address') }}</textarea>
                                @error('institute_address')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-7">
                        <div class="mb-6 flex items-start gap-4">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-sky-50 text-lg text-sky-700 dark:bg-sky-950 dark:text-sky-300">♙</span>
                            <div><h3 class="font-semibold text-slate-900 dark:text-white">First institute administrator</h3><p class="mt-1 text-sm text-slate-500">This person signs in to manage the academy. These are not the institute's public contact details.</p></div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Administrator name <span class="text-rose-500">*</span></label>
                                <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" placeholder="Full name" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('name')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Login email <span class="text-rose-500">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" placeholder="admin@example.com" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('email')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Temporary password <span class="text-rose-500">*</span></label>
                                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('password')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Confirm password <span class="text-rose-500">*</span></label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Enter it again" class="mt-1.5 w-full rounded-xl border-slate-300 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            </div>
                        </div>
                    </section>

                    <div class="flex flex-col-reverse items-stretch justify-end gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('platform.dashboard') }}" class="rounded-xl px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Cancel</a>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Create institute and admin</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
