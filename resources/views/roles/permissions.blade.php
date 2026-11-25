@extends('layouts.app')

@section('title', 'Role Permissions | '.$institute->name)

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div><p class="text-sm font-medium text-indigo-600">{{ $institute->name }}</p><h1 class="mt-1 text-3xl font-bold text-slate-950 dark:text-white">Role permissions</h1><p class="mt-2 text-sm text-slate-600">Read-only access matrix for this institute's role templates.</p></div>
        <a href="{{ route('team.index') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Manage staff roles</a>
    </div>

    <div class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-950"><tr><th class="sticky left-0 min-w-64 bg-slate-50 px-5 py-4 dark:bg-slate-950">Module permission</th>@foreach ($roles as $role)<th class="min-w-36 px-5 py-4 text-center">{{ $role->name }}</th>@endforeach</tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($permissionGroups as $group)
                        <tr class="bg-slate-50/70 dark:bg-slate-950/50"><th colspan="{{ $roles->count() + 1 }}" class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-slate-500">{{ $group->name }}</th></tr>
                        @foreach ($group->children as $permission)
                            <tr><td class="sticky left-0 bg-white px-5 py-3 text-slate-700 dark:bg-slate-900 dark:text-slate-200">{{ $permission->name }}</td>@foreach ($roles as $role)<td class="px-5 py-3 text-center">@if (in_array($permission->id, $permissionIdsByRole->get($role->id, []), true))<span class="inline-grid size-6 place-items-center rounded-full bg-emerald-50 font-bold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300" aria-label="Granted">✓</span>@else<span class="text-slate-300 dark:text-slate-600" aria-label="Not granted">—</span>@endif</td>@endforeach</tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="border-t border-slate-200 px-5 py-4 text-xs text-slate-500 dark:border-slate-800">Role templates follow the approved baseline. Institute Admin may assign staff roles, but cannot grant Super Admin or edit the permission catalog.</p>
    </div>
</div>
@endsection
