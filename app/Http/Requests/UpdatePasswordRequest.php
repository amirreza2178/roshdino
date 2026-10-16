<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:12', 'max:255', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'رمز فعلی را بنویس.',
            'current_password.current_password' => 'رمز فعلی درست نیست.',
            'password.required' => 'رمز تازه را بنویس.',
            'password.min' => 'رمز تازه حداقل ۱۲ نویسه باشد.',
            'password.confirmed' => 'تکرار رمز تازه یکی نیست.',
        ];
    }
}
