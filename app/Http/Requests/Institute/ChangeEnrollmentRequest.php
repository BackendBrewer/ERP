<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-institute-admin') ?? false;
    }

    public function rules(): array
    {
        $rules = [
            'enrollment_id' => ['required', 'integer', 'min:1'],
        ];

        if ($this->route('action') !== 'withdraw') {
            $rules['academy_class_id'] = ['required', 'integer', 'min:1'];
            $rules['section_id'] = ['required', 'integer', 'min:1'];
            $rules['subject_group_id'] = ['nullable', 'integer', 'min:1'];
        }

        return $rules;
    }
}