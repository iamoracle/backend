<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailValidationRule;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'max:255', new EmailValidationRule(), 'unique:users,email'],
            'password' => 'required|string|min:8',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('email')) {
            // Lowercase email before validation
            $this->merge([
                'email' => strtolower($this->input('email')),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The provided email could not be accepted.',
        ];
    }
}
