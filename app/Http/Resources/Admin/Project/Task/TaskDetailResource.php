<?php

namespace App\Http\Resources\Admin\Project\Task;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\Tag\TagResource;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Project\FilesResource;
use App\Http\Resources\Admin\Project\Task\Status\StatusOptionResource;

class TaskDetailResource extends JsonResource
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

        $startDate = Carbon::parse($this->created_at);
        $dueDate = Carbon::parse($this->due_date);

        return $this->resource ? [
            'id' => $this->id,
            'title' => $this->title,
            'created_by' => $this->project->client->name,
            'project' => $this->project->name,
            'description' => $this->description,
            'started_at' => $this->created_at->format('d M, Y'),
            'created_on' => $this->created_at->format('d-m-Y'),
            'due_date' => $dueDate->format('d M, Y'),
            'edit_due_date' => $dueDate->format('Y-m-d'),
            'modified_priority' => ucfirst($this->priority),
            'priority' => $this->priority,
            'priority_color' => $this->priorityColor($this->priority),
            'task_duration' => $this->created_at->diffForHumans($dueDate, ['parts' => 2]),
            'users' => UserResource::collection($this->users),
            'tags' => TagResource::collection($this->tags),
            'files' => FilesResource::collection($this->files),
            'estimated_time' => $this->estimated_time,
            'estimate_time_type' => $this->time_type,
            'status' => new StatusOptionResource($this->status),
        ] : null;
    }
}
