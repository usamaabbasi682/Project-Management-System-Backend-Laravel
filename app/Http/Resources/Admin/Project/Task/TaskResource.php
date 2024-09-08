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
                return '#990e0e';
            case 'high':
                return '#cc9022 ';
            case'medium':
                return '#9b9b04';
            case 'low':
                return'#0f490f';
            case 'lowest':
                return '#6d6464';
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
