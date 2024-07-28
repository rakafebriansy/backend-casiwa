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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'ktp_image' => $this->ktp_image ?? null,
            'starting_year' => $this->starting_year,
            'account_number' => $this->account_number ?? null,
            'university' => $this->university,
            'study_program' => $this->study_program,
            'bank' => $this->bank ?? null,
        ];
    }
}
