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
            'bio' => $this->bio,
            'phone number' => $this->phone_number,
            'address' => $this->address,
            'gender' => $this->gender,
            'profile image' => $this->getImage(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
