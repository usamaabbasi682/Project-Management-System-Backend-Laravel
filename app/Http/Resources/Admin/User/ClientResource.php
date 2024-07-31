<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'department' => $this->department->name ?? '',
            'department_id' => $this->department_id ?? '',
            'color' => $this->department->color ?? '',
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'website' => $this->website ?? '',
            'created_at' => $this->created_at->format('d M, Y') ?? '',
            'profile_image' => !empty($this->image) ? asset('storage'.$this->image->url) : asset('assets/images/client.png') ?? '',
        ] : null;
    }
}
