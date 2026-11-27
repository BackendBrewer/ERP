@extends('layouts.app')

@section('title', 'Academies')

@section('content')
    <x-page-header title="Academies" description="Manage academy workspaces and their Institute Admin access." eyebrow="Platform management"
        :breadcrumbs="[['label' => 'Workspace', 'url' => route('super-admin.dashboard')], ['label' => 'Academies']]">
        <a class="button button-primary" href="{{ route('super-admin.academies.create') }}"><x-icon name="plus" /> Create academy</a>
    </x-page-header>
    @include('partials.status')

    <x-card title="Academy directory" subtitle="Browse the workspaces managed on this platform." class="directory">
        <div class="table-toolbar">
            <span class="muted">{{ $academies->count() }} shown on this page</span>
            @if ($academies->count())
                <label class="table-search"><x-icon name="search" /><input type="search" data-table-search="academies-table" placeholder="Search academies…" aria-label="Search academies"></label>
            @endif
        </div>

        @if ($academies->count())
            <div class="table-wrap">
                <table class="directory-table" id="academies-table" data-global-searchable>
                    <caption class="sr-only">Academies and their actions</caption>
                    <thead><tr><th scope="col">Academy</th><th scope="col">Workspace slug</th><th scope="col">Access</th></tr></thead>
                    <tbody>
                        @foreach ($academies as $academy)
                            <tr data-searchable>
                                <th scope="row">
                                    <span class="row-identity">
                                        <span class="tile-icon" aria-hidden="true">{{ mb_substr($academy->name, 0, 1) }}</span>
                                        {{ $academy->name }}
                                    </span>
                                </th>
                                <td><code>{{ $academy->slug }}</code></td>
                                <td><a class="button button-secondary" href="{{ route('super-admin.academies.institute-admins.create', $academy) }}">Add Institute Admin<span class="sr-only"> for {{ $academy->name }}</span></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="search-empty" data-search-empty hidden>No academies match your search.</div>
            </div>
        @else
            <x-empty-state
                :title="$academies->currentPage() > 1 ? 'No academies on this page' : 'No academies yet'"
                :description="$academies->currentPage() === 1 ? 'Create an academy to set up its workspace and staff access.' : null"
                icon="building"
            >
                @if ($academies->currentPage() === 1)
                    <a class="button button-primary" href="{{ route('super-admin.academies.create') }}"><x-icon name="plus" /> Create academy</a>
                @endif
            </x-empty-state>
        @endif

        @include('partials.pagination', ['page' => $academies, 'label' => 'Academy pages'])
    </x-card>
@endsection
