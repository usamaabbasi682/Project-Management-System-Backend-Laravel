<?php

namespace App\Http\Resources\Admin\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'client' => $this->client->name ?? '',
            'name' => $this->name ?? '',
            'prefix' => $this->prefix ?? '',
            'status' => $this->status ?? '',
            'status_color' => $this->status_color ?? '',
            'color' => $this->color ?? '',
            'status_modified' => Str::upper($this->status) ?? '',
            'users' => UserResource::collection($this->users) ?? '',
            'created_at' => $this->created_at->format('d M, Y h:i A') ?? '',
        ] : null;
    }
}
