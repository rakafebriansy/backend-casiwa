<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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
            'first_name' => 'required|max:60',
            'last_name' => 'max:60|nullable',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'starting_year' => 'required|string|size:4',
            'university_id' => 'required|numeric',
            'study_program_id' => 'required|numeric',
        ];
    }
    public function messages()
    {
        return [
            'first_name.required' => 'Nama Depan tidak boleh kosong',
            'email.required' => 'Alamat Email tidak boleh kosong',
            'password.required' => 'Kata Sandi tidak boleh kosong',
            'starting_year.required' => 'Tahun Masuk Perkuliahan tidak boleh kosong',
            'university_id.required' => 'Universitas belum dipilih',
            'study_program_id.required' => 'Program Studi belum dipilih',
            'first_name.max' => 'Nama Depan harus berjumlah maksimal 60 karakter',
            'last_name.max' => 'Nama Belakang harus berjumlah maksimal 60 karakter',
            'email.email' => 'Alamat Email tidak valid',
            'password.email' => 'Kata Sandi harus berjumlah minimal 8 karakter',
            'starting_year.email' => 'Tahun Masuk Perkuliahan harus berupa angka',
            'university_id.numeric' => 'Universitas belum dipilih',
            'study_program_id.numeric' => 'Program Studi belum dipilih',
            'starting_year.size' => 'Tahun Masuk Perkuliahan harus berjumlah 4 digit',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ],400));
    }
}
