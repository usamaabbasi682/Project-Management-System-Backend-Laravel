<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        return $this->resource ? [
            'id' => $this->id ?? '',
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'created_at' => $this->created_at->format('d M, Y') ?? '',
            'avatar' => !empty($this->image) ? asset('storage'.$this->image->url) : asset('assets/images/client.png') ?? ''
        ] : null;
    }
}
