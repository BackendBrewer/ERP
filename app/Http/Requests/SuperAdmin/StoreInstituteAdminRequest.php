<?php

namespace App\Http\Requests\SuperAdmin;

use Closure;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInstituteAdminRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],

            'email' => ['bail', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            
            'password' => ['bail', 'required', 'string', 'min:8', 'confirmed',
                function (string $attribute, string $value, Closure $fail): void {
                    if (strlen($value) > 72) {
                        $fail('The password must be 72 bytes or fewer.');
                    }
                },
            ],
        ];
    }
}
