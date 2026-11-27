@extends('layouts.app')

@section('title', $student->name)

@section('content')
    @php($latest = $student->latestEnrollment)

    <x-page-header
        :title="$student->name"
        :description="'Student #' . $student->id"
        eyebrow="Admissions"
        :breadcrumbs="[
            ['label' => 'Workspace', 'url' => route('institute.dashboard')],
            ['label' => 'Students', 'url' => route('institute.students.index')],
            ['label' => $student->name],
        ]"
    >
        @if ($latest)
            @php($actions = $latest->ended_on ? ['rejoin'] : ['transfer', 'withdraw'])

            @foreach ($actions as $action)
                <a class="button {{ $action === 'withdraw' ? 'button-secondary' : 'button-primary' }}"
                   href="{{ route('institute.students.enrollment.create', [
                       'student' => $student->id,
                       'action' => $action,
                   ]) }}">
                    {{ ucfirst($action) }}
                </a>
            @endforeach
        @endif
    </x-page-header>

    @include('partials.status')

    <div class="management-grid">
        <x-card title="Enrollment history" class="directory">
            @if ($enrollments->count())
                <div class="table-wrap">
                    <table class="directory-table">
                        <caption class="sr-only">Student enrollment history</caption>
                        <thead>
                            <tr>
                                <th scope="col">Admission date</th>
                                <th scope="col">Class</th>
                                <th scope="col">Section</th>
                                <th scope="col">Subject group</th>
                                <th scope="col">End date</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <time datetime="{{ $enrollment->admitted_on->toDateString() }}">
                                            {{ $enrollment->admitted_on->format('d M Y') }}
                                        </time>
                                    </td>
                                    <td>{{ $enrollment->section->academyClass->name }}</td>
                                    <td>{{ $enrollment->section->name }}</td>
                                    <td>{{ $enrollment->subjectGroup?->name ?? '—' }}</td>
                                    <td>
                                        @if ($enrollment->ended_on)
                                            <time datetime="{{ $enrollment->ended_on->toDateString() }}">
                                                {{ $enrollment->ended_on->format('d M Y') }}
                                            </time>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <x-status-badge
                                            :label="$enrollment->ended_on
                                                ? ucfirst($enrollment->end_reason ?? 'Closed')
                                                : 'Open'"
                                            :variant="$enrollment->ended_on ? 'info' : 'success'"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-empty-state title="No enrollments on this page" icon="school" />
            @endif

            @include('partials.pagination', ['page' => $enrollments, 'label' => 'Enrollment pages'])
        </x-card>

        <x-card title="Contact details" class="form-card">
            <dl>
                <dt class="muted">Guardian</dt>
                <dd>{{ $student->guardian_name ?? '—' }}</dd>
                <dt class="muted">Contact number</dt>
                <dd>{{ $student->contact_phone }}</dd>
            </dl>
        </x-card>
    </div>
@endsection