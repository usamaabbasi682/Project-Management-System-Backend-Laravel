<?php

namespace App\Http\Resources\Admin\Project;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Project\FilesResource;

class ProjectDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        $createdAt = Carbon::parse($this->created_at);
        $updatedAt = Carbon::parse($this->updated_at);

        return $this->resource ? [
            'id' => $this->id ?? '',
            'name' => $this->name ?? '',
            'prefix' => $this->prefix ?? '',
            'client' => $this->client->name ?? '',
            'client_id' => $this->client_id ?? '',
            'budget' => $this->budget ?? '',
            'budget_type' => $this->budget_type ?? '',
            'currency' => $this->currency ?? '',
            'description' => $this->description ?? '',
            'status' => $this->status ?? '',
            'status_color' => $this->status_color ?? '',
            'color' => $this->color ?? '',
            'status_modified' => Str::upper($this->status) ?? '',
            'users' => UserResource::collection($this->users) ?? '',
            'creation' => $createdAt->diffForHumans() ?? '',
            'last_modified' => $updatedAt->diffForHumans() ?? '',
            'files' => FilesResource::collection($this->files) ?? '',
            'total_files' => $this->files->count() ?? '',
        ] : null;
    }
}
