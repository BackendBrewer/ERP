@extends('layouts.app')

@section('title', 'New Admission')

@section('content')
    <x-page-header
        title="New Admission"
        :description="$source ? 'Review the inquiry details and assign a section.' : 'Register a student and assign a section.'"
        eyebrow="Admissions"
        :breadcrumbs="[
            ['label' => 'Workspace', 'url' => route('institute.dashboard')],
            ['label' => 'Admission Inquiries', 'url' => route('institute.admission-inquiries.index')],
            ['label' => 'New Admission'],
        ]"
    />

    <x-card title="Admission details" class="form-card">
        <form class="form-stack" method="POST"
              action="{{ route('institute.admissions.store', $source ? ['inquiry' => $source->id] : []) }}"
              data-loading-form data-admission-form>
            @csrf

            @include('institute.students.partials.details-fields', ['record' => null])

            <fieldset class="workflow-fieldset">
                <legend>Academic details</legend>
                @include('institute.students.partials.placement', ['defaultClassId' => $source?->academy_class_id])

                <div class="form-grid">
                    @include('institute.students.partials.field', ['key' => 'admitted_on', 'label' => 'Admission date', 'type' => 'date', 'value' => today('Asia/Karachi')->toDateString(), 'required' => true, 'length' => null])
                    @include('institute.students.partials.field', ['key' => 'attendance_starts_on', 'label' => 'Attendance start date', 'type' => 'date', 'value' => null, 'required' => false, 'length' => null])
                    @include('institute.students.partials.field', ['key' => 'roll_number', 'label' => 'Roll number', 'type' => 'text', 'value' => null, 'required' => false, 'length' => 40])
                </div>
                <p class="help">Attendance starts on the admission date if no start date is entered.</p>

                @if ($subjects->isNotEmpty())
                    <details @if ($errors->has('subject_ids') || $errors->has('subject_ids.*') || old('subject_ids')) open @endif>
                        <summary>Individual subjects</summary>
                        <p class="help">Select subjects to personalize this enrollment. With no picks, the subject group supplies the subjects.</p>
                        <div class="subject-group-options">
                            @foreach ($subjects as $subject)
                                <label>
                                    <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                        @checked(in_array($subject->id, (array) old('subject_ids', [])))>
                                    {{ $subject->name }}
                                </label>
                            @endforeach
                        </div>
                        @error('subject_ids')<p class="field-error" role="alert">{{ $message }}</p>@enderror
                        @error('subject_ids.*')<p class="field-error" role="alert">{{ $message }}</p>@enderror
                    </details>
                @endif
            </fieldset>

            <div class="form-actions">
                <button class="button button-primary" type="submit">
                    Save admission <x-icon name="arrow-right" />
                </button>
                <a class="button button-secondary"
                   href="{{ route('institute.admission-inquiries.index') }}">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection

@push('scripts')
    <script src="{{ asset('js/admission-form.js') }}" defer></script>
@endpush
