<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->whenNotNull($this->last_name),
            'email' => $this->email,
            'password' => $this->password,
            'ktp_image' => $this->whenNotNull($this->ktp_image),
            'account_number' => $this->whenNotNull($this->account_number),
            'free_download' => $this->whenNotNull($this->free_download),
            'starting_year' => $this->starting_year,
            'university' => [
                'id' => $this->university_id,
                'name' => $this->university,
            ],
            'study_program' => [
                'id' => $this->study_program_id,
                'name' => $this->study_program,
            ],
            'bank' => [
                'id' => $this->bank_id,
                'name' => $this->bank,
            ]
        ];
    }
}
