<?php

namespace App\Http\Dtos\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailValidationRule;

class RegisterUserDto extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'max:255', 'email', new EmailValidationRule(), 'unique:users,email'],
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
