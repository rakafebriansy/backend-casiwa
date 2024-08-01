<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminEditPasswordRequest extends FormRequest
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
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ];
    }
    public function messages()
    {
        return [
            'password.required' => 'Kata Sandi tidak boleh kosong',
            'confirm_password.required' => 'Konfirmasi kata sandi tidak boleh kosong',
            'confirm_password.same' => 'Konfirmasi Kata Sandi tidak sesuai',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ],400));
    }
}
