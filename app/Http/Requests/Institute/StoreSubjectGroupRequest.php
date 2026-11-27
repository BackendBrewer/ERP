<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-institute-admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'academy_class_id' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:100'],
            'subject_ids' => ['required', 'array', 'list', 'min:1'],
            'subject_ids.*' => ['required', 'integer', 'min:1', 'distinct'],
        ];
    }
}