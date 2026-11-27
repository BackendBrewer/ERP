@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <x-page-header
        title="Students"
        eyebrow="Admissions"
        :breadcrumbs="[
            ['label' => 'Workspace', 'url' => route('institute.dashboard')],
            ['label' => 'Students'],
        ]"
    >
        <a class="button button-primary" href="{{ route('institute.admissions.create') }}">
            Direct Admission <x-icon name="arrow-right" />
        </a>
    </x-page-header>

    @include('partials.status')

    <x-card title="Student directory" class="directory">
        <div class="table-toolbar">
            <span class="muted">{{ $students->count() }} shown on this page</span>

            @if ($students->count())
                <label class="table-search">
                    <x-icon name="search" />
                    <input type="search"
                           data-table-search="students-table"
                           placeholder="Search students…"
                           aria-label="Search students on this page">
                </label>
            @endif
        </div>

        @if ($students->count())
            <div class="table-wrap">
                <table class="directory-table" id="students-table" data-global-searchable>
                    <caption class="sr-only">Students and their latest enrollments</caption>

                    <thead>
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Latest class and section</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($students as $student)
                            @php($section = $student->latestEnrollment?->section)

                            <tr data-searchable>
                                <th scope="row">
                                    <a href="{{ route('institute.students.show', ['student' => $student->id]) }}">
                                        {{ $student->name }}
                                    </a>
                                    <span class="table-secondary-text">Student #{{ $student->id }}</span>
                                </th>

                                <td>{{ $student->contact_phone }}</td>

                                <td>
                                    @if ($section)
                                        {{ $section->academyClass->name }} · {{ $section->name }}
                                    @else
                                        <span class="muted">No enrollment</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="search-empty" data-search-empty hidden>
                    No students match your search.
                </div>
            </div>
        @else
            <x-empty-state title="No students on this page" icon="school" />
        @endif

        @include('partials.pagination', ['page' => $students, 'label' => 'Student pages'])
    </x-card>
@endsection