<?php

namespace App\Http\Requests\Institute;

class StoreAdmissionRequest extends StudentDetailsRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'academy_class_id' => ['required', 'integer', 'min:1'],
            'section_id' => ['required', 'integer', 'min:1'],
            'subject_group_id' => ['nullable', 'integer', 'min:1'],
            'admitted_on' => ['required', 'date_format:Y-m-d', ...($this->filled('date_of_birth') ? ['after_or_equal:date_of_birth'] : [])],
            'attendance_starts_on' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:admitted_on'],
            'roll_number' => ['nullable', 'string', 'max:40'],
            'subject_ids' => ['sometimes', 'array', 'list'],
            'subject_ids.*' => ['required', 'integer', 'min:1', 'distinct'],
        ];
    }
}
