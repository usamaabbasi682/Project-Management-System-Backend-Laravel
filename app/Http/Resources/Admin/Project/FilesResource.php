<?php

namespace App\Http\Resources\Admin\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        $explode = explode('.', $this->url);
        $extension = end($explode);
        return $this->resource ? [
            'id' => $this->id ?? '',
            'url' => asset('storage'.$this->url) ?? '',
            'extension' => $extension ?? '',
        ] : null;
    }
}
