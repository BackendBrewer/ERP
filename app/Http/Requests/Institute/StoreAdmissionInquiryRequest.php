<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdmissionInquiryRequest extends FormRequest
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
                'bail',
                'required',
                'string',
                'max:25',
                'regex:/\A\+?[0-9](?:[0-9 ()-]*[0-9])?\z/',
            ],
            'academy_class_id' => [
                'bail',
                'nullable',
                'integer',
                Rule::exists('academy_classes', 'id')->where(
                    'academy_id',
                    $this->user()->academy_id
                ),
            ],
            'follow_up_on' => ['nullable', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_phone.regex' => 'Enter a contact number such as 0300 1234567.',
            'academy_class_id.exists' => 'Select a class from your academy.',
        ];
    }
}