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

            <div class="form-grid">
                @foreach ([
                    ['student_name', 'Student name', $source?->student_name, 150, true, 'text'],
                    ['guardian_name', 'Guardian name', $source?->guardian_name, 150, false, 'text'],
                    ['contact_phone', 'Contact number', $source?->contact_phone, 25, true, 'tel'],
                ] as [$field, $label, $default, $length, $required, $type])
                    <div class="field">
                        <label for="{{ $field }}">
                            {{ $label }}
                            @if ($required)<span class="required-mark" aria-hidden="true">*</span>@endif
                        </label>
                        <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}"
                               value="{{ old($field, $default) }}" maxlength="{{ $length }}"
                               @if ($required) required @endif
                               @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                        @error($field)
                            <p class="field-error" id="{{ $field }}-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="field">
                    <label for="admitted_on">Admission date</label>
                    <input id="admitted_on" name="admitted_on" type="date"
                           value="{{ old('admitted_on', today('Asia/Karachi')->toDateString()) }}" required>
                    @error('admitted_on')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="academy_class_id">Class</label>
                    <select id="academy_class_id" name="academy_class_id" required data-admission-class>
                        <option value="">Select a class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}"
                                @selected((string) old('academy_class_id', $source?->academy_class_id) === (string) $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academy_class_id')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="section_id">Section</label>
                    <select id="section_id" name="section_id" required data-admission-dependent>
                        <option value="">Select a section</option>
                        @foreach ($classes as $class)
                            @foreach ($class->sections as $section)
                                <option value="{{ $section->id }}" data-class-id="{{ $class->id }}"
                                    @selected((string) old('section_id') === (string) $section->id)>
                                    {{ $class->name }} — {{ $section->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('section_id')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="subject_group_id">Subject group</label>
                    <select id="subject_group_id" name="subject_group_id" data-admission-dependent>
                        <option value="">No subject group</option>
                        @foreach ($classes as $class)
                            @foreach ($class->subjectGroups as $group)
                                <option value="{{ $group->id }}" data-class-id="{{ $class->id }}"
                                    @selected((string) old('subject_group_id') === (string) $group->id)>
                                    {{ $class->name }} — {{ $group->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('subject_group_id')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

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