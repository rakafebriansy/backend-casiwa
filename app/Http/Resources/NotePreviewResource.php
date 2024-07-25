<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotePreviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail_name' => $this->thumbnail_name,
            'name' => $this->first_name . ' ' . $this->last_name,
            'study_program' => $this->study_program,
            'university' => $this->university,
            'download_count' => $this->download_count,
            'date' => date('d-m-Y',strtotime($this->created_at)),
        ];
    }
}
