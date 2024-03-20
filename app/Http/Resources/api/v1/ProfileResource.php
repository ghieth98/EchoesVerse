<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user?->name,
            'email' => $this->user?->email,
            'bio' => $this->bio,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'gender' => $this->gender
        ];
    }
}
