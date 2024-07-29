<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;

class EditProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
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
            'password' => 'nullable|min:8',
            'confirm_password' => 'same:password|nullable',
            'starting_year' => 'required|string|size:4',
            'university_id' => 'required|numeric',
            'study_program_id' => 'required|numeric',
            'bank_id' => 'nullable|numeric',
            'account_number ' => 'nullable|numeric',
            'ktp_image ' => 'nullable|mimes:png,jpg|max:1024',
        ];
    }
    public function messages()
    {
        return [
            'first_name.required' => 'Nama Depan tidak boleh kosong',
            'email.required' => 'Alamat Email tidak boleh kosong',
            'starting_year.required' => 'Tahun Masuk Perkuliahan tidak boleh kosong',
            'university_id.required' => 'Universitas belum dipilih',
            'study_program_id.required' => 'Program Studi belum dipilih',
            'first_name.max' => 'Nama Depan harus berjumlah maksimal 60 karakter',
            'last_name.max' => 'Nama Belakang harus berjumlah maksimal 60 karakter',
            'email.email' => 'Alamat Email tidak valid',
            'password.min' => 'Kata Sandi harus berjumlah minimal 8 karakter',
            'starting_year.string' => 'Tahun Masuk Perkuliahan harus berupa angka',
            'university_id.numeric' => 'Universitas belum dipilih',
            'study_program_id.numeric' => 'Program Studi belum dipilih',
            'starting_year.size' => 'Tahun Masuk Perkuliahan harus berjumlah 4 digit',
            'confirm_password.same' => 'Konfirmasi Kata Sandi tidak sesuai',
            'ktp_image.mimes' => 'Foto KTP harus memiliki ekstensi png atau jpg',
            'ktp_image.max' => 'Foto KTP harus memiliki ukuran maksimal 1MB',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ],400));
    }
}
