<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'same:password',
        ];
    }
    public function messages()
    {
        return [
            'password.required' => 'Kata Sandi tidak boleh kosong',
            'password.min' => 'Kata Sandi harus berjumlah minimal 8 karakter',
            'confirm_password.same' => 'Konfirmasi Kata Sandi tidak sesuai',
        ];
    }
}
