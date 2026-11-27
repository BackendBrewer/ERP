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

    @include('partials.workflow-tabs', [
        'label' => 'Admissions',
        'tabs' => [
            ['Students', 'institute.students.index'],
            ['Admission Inquiries', 'institute.admission-inquiries.index'],
        ],
    ])

    @include('partials.status')

    <x-card title="Student directory" class="directory">
        <form class="form-stack directory-filters" method="GET"
              action="{{ route('institute.students.index') }}" data-student-directory>
            <div class="table-toolbar">
                <label class="table-search">
                    <x-icon name="search" />
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                           maxlength="100" placeholder="Name, admission number or phone…"
                           aria-label="Search all students">
                </label>
                <button class="button button-primary" type="submit">Apply</button>
                <a class="button button-secondary" href="{{ route('institute.students.index') }}">Clear</a>
            </div>
            <div class="directory-filter-grid">
                <div class="field">
                    <label for="class_id">Class</label>
                    <select id="class_id" name="class_id">
                        <option value="">All classes</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected((string) ($filters['class_id'] ?? '') === (string) $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="section_id">Section</label>
                    <select id="section_id" name="section_id">
                        <option value="">All sections</option>
                        @foreach ($classes as $class)
                            @foreach ($class->sections as $section)
                                <option value="{{ $section->id }}" @selected((string) ($filters['section_id'] ?? '') === (string) $section->id)>{{ $class->name }} — {{ $section->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="status">Enrollment status</label>
                    <select id="status" name="status">
                        <option value="">All statuses</option>
                        @foreach (['active' => 'Active', 'closed' => 'Closed', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="from">Latest admission from</label>
                    <input id="from" name="from" type="date" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="field">
                    <label for="to">Latest admission to</label>
                    <input id="to" name="to" type="date" value="{{ $filters['to'] ?? '' }}">
                </div>
            </div>

            @foreach ($errors->all() as $message)
                <p class="field-error" role="alert">{{ $message }}</p>
            @endforeach
        </form>

        <p class="field-error directory-feedback" data-directory-feedback role="status" hidden></p>
        <div id="student-directory-results" aria-live="polite">
            <p class="muted directory-feedback">{{ $students->count() }} shown on this page</p>
            @if ($students->count())
                <div class="table-wrap">
                    <table class="directory-table" id="students-table">
                        <caption class="sr-only">Students and their latest enrollments</caption>

                        <thead>
                            <tr>
                                <th scope="col">Student</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Latest class and section</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($students as $student)
                                @php($enrollment = $student->latestEnrollment)
                                @php($section = $enrollment?->section)

                                <tr>
                                    <th scope="row">
                                        <a href="{{ route('institute.students.show', ['student' => $student->id]) }}">
                                            {{ $student->name }}
                                        </a>
                                        <span class="table-secondary-text">{{ $student->admission_number ?? ('Student #' . $student->id) }}</span>
                                    </th>

                                    <td>{{ $student->contact_phone }}</td>

                                    <td>
                                        @if ($section)
                                            {{ $section->academyClass->name }} · {{ $section->name }}
                                        @else
                                            <span class="muted">No enrollment</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($enrollment)
                                            @php($statusLabel = $enrollment->ended_on ? 'Closed' : ($enrollment->admitted_on->toDateString() > $today ? 'Scheduled' : 'Active'))
                                            <x-status-badge :label="$statusLabel" :variant="$statusLabel === 'Active' ? 'success' : 'info'" />
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @else
                <x-empty-state :title="array_filter($filters) ? 'No matching students on this page' : 'No students on this page'" icon="school" />
            @endif

            @include('partials.pagination', ['page' => $students, 'label' => 'Student pages'])
        </div>
    </x-card>
@endsection

@push('scripts')
    <script src="{{ asset('js/student-directory.js') }}" defer></script>
@endpush
