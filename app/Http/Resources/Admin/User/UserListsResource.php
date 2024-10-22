<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListsResource extends JsonResource
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
            'role' => $this->getRoleNames()[0] ?? '',
            'total_projects' => $this->projects_count ?? '',
            'total_active_tasks' => $this->tasks_count ?? '',
            'email_verified_at' => $this->email_verified_at != null ? (boolean) true : (boolean) false,
            'avatar' => !empty($this->image) ? asset('storage'.$this->image->url) : asset('assets/images/client.png') ?? ''
        ] : null;
    }
}
