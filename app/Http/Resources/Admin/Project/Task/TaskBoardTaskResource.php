<?php

namespace App\Http\Resources\Admin\Project\Task;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\Tag\TagResource;
use App\Http\Resources\Admin\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Project\FilesResource;

class TaskBoardTaskResource extends JsonResource
{
    private function getTaskStatus(Carbon $now, Carbon $dueDate): string
    {
        if ($now->isSameDay($dueDate)) {
            return "Today";
        } elseif ($now->gt($dueDate)) {
            return "0 Minutes";
        } else {
            $timeLeft = $now->diffForHumans($dueDate, [
                'parts' => 2,
                'short' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE
            ]);
            return $timeLeft . " Remaining";
        }
    }

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
        $now = Carbon::now('Asia/Karachi');
        $dueDate = Carbon::parse($this->due_date);

        $status = $this->getTaskStatus($now, $dueDate);

        return $this->resource ? [
            'id' => $this->id,
            'title' => $this->title,
            'due_date' => $dueDate->format('d M'),
            'time_left' => $status,

            'created_by' => $this->project->client->name,
            'project' => $this->project->name,
            'description' => $this->description,
            'started_at' => $this->created_at->format('d M, Y'),
            'created_on' => $this->created_at->format('d-m-Y'),
            'edit_due_date' => $dueDate->format('Y-m-d'),
            'modified_priority' => ucfirst($this->priority),
            'priority' => $this->priority,
            'priority_color' => $this->priorityColor($this->priority),
            'users' => UserResource::collection($this->users),
            'tags' => TagResource::collection($this->tags),
            'files' => FilesResource::collection($this->files),
            'estimated_time' => $this->estimated_time,
            'estimate_time_type' => $this->time_type,

            'comments_count' => $this->comments()->count(),
            'files_count' => $this->files()->count(),
        ] : null;
    }
}
