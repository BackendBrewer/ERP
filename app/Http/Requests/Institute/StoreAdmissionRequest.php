<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-institute-admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:150'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'contact_phone' => [
                'required',
                'string',
                'max:25',
                'regex:/\A\+?[0-9](?:[0-9 ()-]*[0-9])?\z/',
            ],
            'academy_class_id' => ['required', 'integer', 'min:1'],
            'section_id' => ['required', 'integer', 'min:1'],
            'subject_group_id' => ['nullable', 'integer', 'min:1'],
            'admitted_on' => ['required', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_phone.regex' => 'Enter a contact number such as 0300 1234567.',
        ];
    }
}