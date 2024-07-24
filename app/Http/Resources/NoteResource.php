<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $this->file_path,
            'name' => $this->first_name . ' ' . $this->last_name,
            'study_program' => $this->study_program,
            'university' => $this->university,
            'created_at' => $this->created_at,
        ];
    }
}