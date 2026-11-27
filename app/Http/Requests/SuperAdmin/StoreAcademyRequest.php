<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('access-super-admin') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'bail',
                'required',
                'string',
                'max:100',
                'regex:/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/',
                Rule::unique('academies', 'slug'),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'slug.regex' => 'Use lowercase letters and numbers separated by single hyphens.',
            'slug.unique' => 'An academy with this slug already exists.',
        ];
    }
}
