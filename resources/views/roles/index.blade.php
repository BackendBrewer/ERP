
@extends('layouts.app')

@section('title', 'Roles | Academy ERP')

@section('content')

<div class="space-y-6">

    <div>
        <a href="{{ route('workspace') }}"
           class="text-sm font-medium text-indigo-600 hover:underline">
            Workspace
        </a>

        <h1 class="mt-3 text-3xl font-bold text-slate-900">
            Roles & Permissions
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            {{ $institute->name }} — Manage staff access.
        </p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Institute Roles
            </h2>
        </div>

        @if ($roles->isEmpty())

            <div class="px-6 py-12 text-center">
                <h3 class="font-semibold text-slate-800">
                    No roles created yet
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    This institute does not have any configured roles.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Role Name</th>
                            <th class="px-6 py-4 font-semibold">Slug</th>
                            <th class="px-6 py-4 font-semibold">Created</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($roles as $role)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $role->name }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $role->slug }}
                                </td>

                                <td class="px-6 py-4 text-slate-500">
                                    {{ $role->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $roles->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
