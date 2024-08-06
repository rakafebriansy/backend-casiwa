<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForgotPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|exists:users,email|email'
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Alamat Email tidak boleh kosong',
            'email.email' => 'Alamat Email tidak valid',
            'email.exists' => 'Alamat Email tidak terdaftar',
        ];
    }

}
