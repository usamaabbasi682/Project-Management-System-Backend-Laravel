<?php

namespace App\Http\Resources\Admin\Project\Task\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        return $this->resource ? [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
            'created_at' => $this->created_at->format('d M Y'),
        ] : null;
    }
}
