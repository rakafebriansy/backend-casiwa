<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnpaidRedeemResource extends JsonResource
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
            'last_name' => $this->last_name,
            'ktp_image' => $this->ktp_image,
            'account_number' => $this->account_number,
            'bank_name' => $this->bank_name,
            'total' => $this->total,
        ];
    }
}
