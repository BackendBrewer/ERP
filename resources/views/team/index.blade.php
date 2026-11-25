@extends('layouts.app')

@section('title', 'Staff Accounts | '.$institute->name)

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div><p class="text-sm font-medium text-indigo-600">{{ $institute->name }}</p><h1 class="mt-1 text-3xl font-bold text-slate-950 dark:text-white">Staff accounts</h1><p class="mt-2 text-sm text-slate-600">Create individual staff logins and assign approved institute roles.</p></div>
        <a href="{{ route('workspace') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">Back to dashboard</a>
    </div>

    @if (session('status'))
        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">{{ session('status') }}</div>
    @endif

    <div class="mt-7 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"><h2 class="font-semibold text-slate-900 dark:text-white">Institute team</h2><p class="mt-1 text-sm text-slate-500">Role changes take effect on the member's next request.</p></div>
            @if ($members->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No active institute members yet.</p>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($members as $member)
                        <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">{{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}</span>
                                <span class="min-w-0"><span class="block truncate font-medium text-slate-900 dark:text-white">{{ $member->name }}</span><span class="block truncate text-sm text-slate-500">{{ $member->email }}</span></span>
                            </div>
                            @if ($member->assignedRole?->slug === 'institute-admin')
                                <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">Institute Admin · platform assigned</span>
                            @else
                                <form method="POST" action="{{ route('team.role.update', $member) }}" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <label class="sr-only" for="role-{{ $member->id }}">Role for {{ $member->name }}</label>
                                    <select id="role-{{ $member->id }}" name="role_id" class="rounded-lg border-slate-300 bg-white py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" @selected((int) $member->pivot->role_id === $role->id)>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-700 dark:bg-indigo-600 dark:hover:bg-indigo-500">Save role</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white">Create staff login</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Each staff member gets an individual account. Institute Admin and Super Admin roles cannot be assigned from this form.</p>

            <form method="POST" action="{{ route('team.store') }}" class="mt-5 space-y-4">
                @csrf
                <div><label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Full name</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" class="mt-1 w-full rounded-lg border-slate-300 bg-white text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">@error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Login email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="mt-1 w-full rounded-lg border-slate-300 bg-white text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">@error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label for="role_id" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Institute role</label><select id="role_id" name="role_id" required class="mt-1 w-full rounded-lg border-slate-300 bg-white text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white"><option value="">Choose a role</option>@foreach ($roles as $role)<option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>@endforeach</select>@error('role_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Temporary password</label><input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="mt-1 w-full rounded-lg border-slate-300 bg-white text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">@error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
                <div><label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 w-full rounded-lg border-slate-300 bg-white text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white"></div>
                <button class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Create staff account</button>
            </form>
        </aside>
    </div>
</div>
@endsection
