<?php

namespace App\Http\Resources\Admin\Project\Task;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    // Priority color
    public function priorityColor() 
    {
        switch ($this->priority) {
            case 'highest':
                return '#ff0000';
            case 'high':
                return '#ffa500 ';
            case'medium':
                return '#ffff00';
            case 'low':
                return'#008000';
            case 'lowest':
                return '#808080';
            default:
                return '#0000ff';
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        $dueDate = Carbon::parse($this->due_date);

        return $this->resource ? [
            'id' => $this->id,
            'title' => $this->title,
            'due_date' => $dueDate->format('d M'),
            'priority' => $this->priority,
            'modified_priority' => ucfirst($this->priority),
            'priority_color' => $this->priorityColor($this->priority),
            'users' => UserResource::collection($this->users),
        ] : null;
    }
}
