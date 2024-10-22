<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Project\ProjectOptionsResource;

class UserListDetailResource extends JsonResource
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
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'salary' => $this->salary ?? '',
            'salary_format' => number_format($this->salary) ?? '',
            'role' => $this->getRoleNames()[0] ?? '',
            'avatar' => !empty($this->image) ? asset('storage'.$this->image->url) : asset('assets/images/client.png') ?? '',
            'projects'=> ProjectOptionsResource::collection($this->projects) ?? '',
            'status' => $this->status ?? '',
            'updated_at' => $this->updated_at->format('d M, Y') ?? '',
            'created_at' => $this->created_at->format('d M, Y') ?? '',
            'pending_tasks' => $this->tasks()->whereHas('status', function ($query) {
                    $query->where('name', 'pending');
            })->count() ?? ''
        ] : null;
    }
}
