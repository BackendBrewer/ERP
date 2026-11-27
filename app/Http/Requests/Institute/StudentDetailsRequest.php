<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StudentDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-institute-admin') ?? false;
    }

    public function rules(): array
    {
        $phone = ['nullable', 'string', 'max:25', 'regex:/\A\+?[0-9](?:[0-9 ()-]*[0-9])?\z/'];

        return [
            'student_name' => ['required', 'string', 'max:150'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'contact_phone' => ['required', ...array_slice($phone, 1)],
            'student_phone' => $phone,
            'guardian_phone' => $phone,
            'whatsapp_phone' => $phone,
            'admission_number' => ['nullable', 'string', 'max:40'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:'.today('Asia/Karachi')->toDateString()],
            'gender' => ['nullable', 'in:female,male,other,prefer_not_to_say'],
            'address' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function studentAttributes(): array
    {
        return ['name' => $this->validated('student_name')] + Arr::only($this->validated(), [
            'guardian_name', 'contact_phone', 'student_phone', 'guardian_phone',
            'whatsapp_phone', 'admission_number', 'date_of_birth', 'gender', 'address', 'email',
        ]);
    }

    public function messages(): array
    {
        return ['*.regex' => 'Enter a contact number such as 0300 1234567.'];
    }
}
