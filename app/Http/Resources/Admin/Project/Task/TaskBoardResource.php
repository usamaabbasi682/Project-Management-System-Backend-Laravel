<?php

namespace App\Http\Resources\Admin\Project\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Project\Task\TaskBoardTaskResource;

class TaskBoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        // Retrieve project and user from the request
        $project = $request->get('project');
        $user = $request->get('user');

        $tasksQuery = $this->tasks()->where('project_id', $project)->with('users');

        if ($user) {
            $tasksQuery->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user);
            });
        }

        // Execute the query and get the tasks
        $tasks = $tasksQuery->get();
        
        return $this->resource ? [
            'id' => $this->id,
            'name' => strtoupper($this->name),
            'order' => $this->order,
            'created_at' => $this->created_at->format('d M Y'),
            'tasks' => TaskBoardTaskResource::collection($tasks)
        ] : null;
    }
}
