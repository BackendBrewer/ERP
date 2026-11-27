<fieldset class="workflow-fieldset">
    <legend>Student details</legend>
    <div class="form-grid">
        @foreach ([
            ['student_name', 'Student name', $record?->name ?? ($source ?? null)?->student_name, 'text', 150, true],
            ['admission_number', 'Admission number', $record?->admission_number, 'text', 40, false],
            ['date_of_birth', 'Date of birth', $record?->date_of_birth?->toDateString(), 'date', null, false],
            ['email', 'Student email', $record?->email, 'email', 255, false],
        ] as [$key, $label, $value, $type, $length, $required])
            @include('institute.students.partials.field', compact('key', 'label', 'value', 'type', 'length', 'required'))
        @endforeach

        <div class="field">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">Select gender</option>
                @foreach (['female' => 'Female', 'male' => 'Male', 'other' => 'Other', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('gender', $record?->gender) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('gender')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2" maxlength="500">{{ old('address', $record?->address) }}</textarea>
            @error('address')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        </div>
    </div>
</fieldset>

<fieldset class="workflow-fieldset">
    <legend>Guardian &amp; contacts</legend>
    <div class="form-grid">
        @foreach ([
            ['guardian_name', 'Guardian name', $record?->guardian_name ?? ($source ?? null)?->guardian_name, 'text', 150, false],
            ['contact_phone', 'Primary contact number', $record?->contact_phone ?? ($source ?? null)?->contact_phone, 'tel', 25, true],
            ['student_phone', 'Student phone', $record?->student_phone, 'tel', 25, false],
            ['guardian_phone', 'Guardian phone', $record?->guardian_phone, 'tel', 25, false],
            ['whatsapp_phone', 'WhatsApp number', $record?->whatsapp_phone, 'tel', 25, false],
        ] as [$key, $label, $value, $type, $length, $required])
            @include('institute.students.partials.field', compact('key', 'label', 'value', 'type', 'length', 'required'))
        @endforeach
    </div>
</fieldset>
