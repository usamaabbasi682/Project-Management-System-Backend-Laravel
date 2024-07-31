<?php

namespace App\Http\Resources\Admin\Department;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'color' => $this->color ?? '',
            'description' => $this->description ?? '',
            'created_at' => $this->created_at->format('d M, Y h:i A') ?? '',
        ] : null;
    }
}
