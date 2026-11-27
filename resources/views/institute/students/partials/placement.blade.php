<div class="form-grid">
    <div class="field">
        <label for="academy_class_id">Class</label>
        <select id="academy_class_id" name="academy_class_id" required data-admission-class>
            <option value="">Select a class</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}"
                    @selected((string) old('academy_class_id', $defaultClassId) === (string) $class->id)>
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
        <select id="subject_group_id" name="subject_group_id" data-admission-dependent data-admission-group>
            <option value="">No subject group</option>
            @foreach ($classes as $class)
                @foreach ($class->subjectGroups as $group)
                    <option value="{{ $group->id }}" data-class-id="{{ $class->id }}"
                        @if ($showGroupPreview ?? false) data-group-subjects="{{ $group->subjects->pluck('name')->sort()->join(', ') }}" @endif
                        @selected((string) old('subject_group_id') === (string) $group->id)>
                        {{ $class->name }} — {{ $group->name }}
                    </option>
                @endforeach
            @endforeach
        </select>
        @if ($showGroupPreview ?? false)
            <p class="help" data-group-preview aria-live="polite" hidden></p>
        @endif
        @error('subject_group_id')
            <p class="field-error" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>
