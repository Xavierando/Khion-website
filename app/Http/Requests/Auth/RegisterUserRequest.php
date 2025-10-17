<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            // Language is now required and must be one of the configured languages
            'language' => 'required|string|in:' . implode(',', array_keys(config('language', []))),
        ];
    }

    public function messages(): array
    {
        return [
            'language.required' => 'A language selection is required.',
            'language.in' => 'The selected language is not supported.',
        ];
    }
}
