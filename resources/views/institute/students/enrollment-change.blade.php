@extends('layouts.app')

@section('title', ucfirst($action) . ' Student')

@section('content')
    <x-page-header
        :title="ucfirst($action) . ' Student'"
        :description="$student->name . ' · Student #' . $student->id"
        eyebrow="Admissions"
        :breadcrumbs="[
            ['label' => 'Students', 'url' => route('institute.students.index')],
            ['label' => $student->name, 'url' => route('institute.students.show', $student->id)],
            ['label' => ucfirst($action)],
        ]"
    />

    <x-card title="Enrollment details" class="form-card">
        <form class="form-stack" method="POST"
              action="{{ route('institute.students.enrollment.store', [
                  'student' => $student->id,
                  'action' => $action,
              ]) }}"
              data-loading-form
              @if ($action !== 'withdraw') data-admission-form @endif>
            @csrf
            <input type="hidden" name="enrollment_id" value="{{ $latest->id }}">

            @error('enrollment_id')
                <p class="field-error" role="alert">{{ $message }}</p>
            @enderror

            @if ($action === 'withdraw')
                <p>Confirm withdrawal to close this student’s current enrollment today.</p>
            @else
                <div class="form-grid">
                    <div class="field">
                        <label for="academy_class_id">Class</label>
                        <select id="academy_class_id" name="academy_class_id" required data-admission-class
                            @error('academy_class_id') aria-invalid="true" aria-describedby="class-error" @enderror>
                            <option value="">Select a class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected((string) old('academy_class_id') === (string) $class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('academy_class_id')
                            <p class="field-error" id="class-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="section_id">Section</label>
                        <select id="section_id" name="section_id" required data-admission-dependent
                            @error('section_id') aria-invalid="true" aria-describedby="section-error" @enderror>
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
                            <p class="field-error" id="section-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="subject_group_id">Subject group</label>
                        <select id="subject_group_id" name="subject_group_id" data-admission-dependent
                            @error('subject_group_id') aria-invalid="true" aria-describedby="group-error" @enderror>
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
                            <p class="field-error" id="group-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="form-actions">
                <button class="button button-primary" type="submit">
                    Confirm {{ $action }}
                </button>
                <a class="button button-secondary"
                   href="{{ route('institute.students.show', $student->id) }}">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection

@if ($action !== 'withdraw')
    @push('scripts')
        <script src="{{ asset('js/admission-form.js') }}" defer></script>
    @endpush
@endif