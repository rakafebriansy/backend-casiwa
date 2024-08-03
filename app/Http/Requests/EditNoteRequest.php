<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;

class EditNoteRequest extends FormRequest
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
            'id' => 'required',
            'title' => 'required|max:60',
            'description' => 'required|max:200',
            'file' => 'mimes:pdf|nullable|max:20480',
            'thumbnail' => 'nullable|mimes:png,jpg',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Judul tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'title.max' => 'Judul harus berjumlah maksimal 60 karakter',
            'description.max' => 'Deskripsi harus berjumlah maksimal 200 karakter',
            'file.max' => 'File harus berukuran maksimal 20 MB',
            'file.mimes' => 'File harus memiliki ekstensi pdf',
            'thumbnail.mimes' => 'Thumbnail harus memiliki ekstensi png atau jpg',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ],400));
    }
}
