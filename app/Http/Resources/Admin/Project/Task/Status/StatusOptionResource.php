<?php

namespace App\Http\Resources\Admin\Project\Task\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        return $this->resource ? [
            'value' => $this->id,
            'label' => $this->name,
        ] : null;
    }
}
