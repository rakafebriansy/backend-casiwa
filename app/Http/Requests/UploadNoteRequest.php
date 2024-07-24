<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadNoteRequest extends FormRequest
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
            'title' => 'required|max:60',
            'description' => 'required|max:200',
            'file' => 'mimes:pdf|required|max:20480',
            'thumbnail' => 'required|mimes:png,jpg',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Judul tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'file.required' => 'Belum ada file yang diunggah',
            'thumbnail.required' => 'Belum ada thumbnail yang diunggah',
            'title.max' => 'Judul harus berjumlah maksimal 60 karakter',
            'description.max' => 'Deskripsi harus berjumlah maksimal 200 karakter',
            'file.max' => 'File harus berukuran maksimal 20 MB',
            'file.mimes' => 'File harus memiliki ekstensi pdf',
            'thumbnail.mimes' => 'Thumbnail harus memiliki ekstensi png',
        ];
    }
}