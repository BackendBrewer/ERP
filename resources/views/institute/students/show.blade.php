@extends('layouts.app')

@section('title', $student->name)

@section('content')
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
        @foreach ($actions as $availableAction)
            <a class="button {{ $availableAction === 'withdraw' ? 'button-secondary' : 'button-primary' }}"
               href="{{ route('institute.students.show', ['student' => $student->id, 'action' => $availableAction]) }}#enrollment-action">
                {{ ucfirst($availableAction) }}
            </a>
        @endforeach
    </x-page-header>

    @include('partials.status')

    @if ($action)
        @include('institute.students.partials.enrollment-change')
    @endif

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
                                <th scope="col">Subjects</th>
                                <th scope="col">Roll no.</th>
                                <th scope="col">Attendance starts</th>
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
                                    <td>
                                        <strong>{{ $enrollment->subjectGroup?->name ?? '—' }}</strong>
                                        @php($subjects = $enrollment->subjects->isNotEmpty() ? $enrollment->subjects : $enrollment->subjectGroup?->subjects)
                                        @if ($subjects?->isNotEmpty())
                                            <span class="table-secondary-text">{{ $subjects->pluck('name')->join(', ') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $enrollment->roll_number ?? '—' }}</td>
                                    <td>{{ ($enrollment->attendance_starts_on ?? $enrollment->admitted_on)->format('d M Y') }}</td>
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
                                                : ($enrollment->admitted_on->toDateString() > $today->toDateString() ? 'Scheduled' : 'Open')"
                                            :variant="$enrollment->ended_on || $enrollment->admitted_on->toDateString() > $today->toDateString() ? 'info' : 'success'"
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

        <x-card title="Student record" class="form-card">
            <dl class="student-details">
                <dt>Admission number</dt><dd>{{ $student->admission_number ?? '—' }}</dd>
                <dt>Date of birth</dt><dd>{{ $student->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                <dt>Gender</dt><dd>{{ $student->gender ? ucfirst(str_replace('_', ' ', $student->gender)) : '—' }}</dd>
                <dt>Address</dt><dd>{{ $student->address ?? '—' }}</dd>
                <dt>Student email</dt><dd>{{ $student->email ?? '—' }}</dd>
                <dt>Guardian</dt><dd>{{ $student->guardian_name ?? '—' }}</dd>
                <dt>Primary contact</dt><dd>{{ $student->contact_phone }}</dd>
                <dt>Student phone</dt><dd>{{ $student->student_phone ?? '—' }}</dd>
                <dt>Guardian phone</dt><dd>{{ $student->guardian_phone ?? '—' }}</dd>
                <dt>WhatsApp</dt><dd>{{ $student->whatsapp_phone ?? '—' }}</dd>
            </dl>

            <details @if (! $action && $errors->any()) open @endif>
                <summary>Edit student details</summary>
                <form class="form-stack" method="POST" action="{{ route('institute.students.update', $student->id) }}" data-loading-form>
                    @csrf
                    @method('PATCH')
                    @include('institute.students.partials.details-fields', ['record' => $student])
                    <button class="button button-primary" type="submit">Save changes</button>
                </form>
            </details>
        </x-card>
    </div>
@endsection

@if (in_array($action, ['transfer', 'rejoin'], true))
    @push('scripts')
        <script src="{{ asset('js/admission-form.js') }}" defer></script>
    @endpush
@endif
