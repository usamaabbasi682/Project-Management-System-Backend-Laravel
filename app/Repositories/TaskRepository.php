<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll($project,Request $request) 
    {
        $query = $project->tasks();
        $query->when($request->has('search'), function ($query) use($request) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        });
        $tasks = $query->orderBy('id','DESC')->paginate(12);
        return $tasks;
    }
 
    public function getById($project,$task) 
    {
        return $project->tasks()->find($task);
    }

    public function create(Request $request, $project) 
    {
        $users = $request->input('users',[]);
        $tags = $request->input('tags',[]);

        $task = $project->tasks()->create(
            $request->safe()->only([
                'title',
                'description',
                'due_date',
                'estimated_time',
                'time_type',
                'priority',
            ])
        );

        if (!empty($users) && is_array($users)) {
            $task->users()->attach($users);
        }

        if (!empty($tags) && is_array($tags)) {
            $task->tags()->attach($tags);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $document = FileUploadService::upload($attachment, $path = '/public/tasks');
                $task->files()->create(['url' =>  $document->uploaded_path.'/'.$document->uploaded_name]);
            }
        }

        return $task;
    }

    public function update(Request $request, $project,$taskId) 
    {
        $task = $project->tasks()->find($taskId);
        $users = $request->input('users',[]);
        $tags = $request->input('tags',[]);
        
        $task->update(
            $request->safe()->only([
                'status_id',
                'title',
                'description',
                'due_date',
                'estimated_time',
                'time_type',
                'priority',
            ])
        );

        if (!empty($users) && is_array($users)) {
            $task->users()->sync($users);
        }

        if (!empty($tags) && is_array($tags)) {
            $task->tags()->sync($tags);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $document = FileUploadService::upload($attachment, $path = '/public/tasks');
                $task->files()->create(['url' =>  $document->uploaded_path.'/'.$document->uploaded_name]);
            }
        }

        return $task;
    }

    public function delete($project,$taskId) 
    {
        $task = $project->tasks()->find($taskId);
        $task->users()->detach();
        $task->tags()->detach(); 
        foreach ($task->files as $file) {
            $url = $file->url;
            FileUploadService::delete('public'.$url);
            $file->delete();
        }
        $task->delete();
    }

    public function getAllComments($project,$task) 
    {
        $tasks = $project->tasks()->find($task)->comments;
        return $tasks;
    }

    public function createComment(Request $request,$project,$taskId) 
    {
        $task = $project->tasks()->find($taskId);
        $comment = $task->comments()->create(
            [
                'user_id' => auth()->user()->id,
                'body' => $request->input('body'),
            ]
        );
        return $comment;
    }

    public function updateComment(Request $request,$project,$taskId,$commentId) 
    {
        $task = $project->tasks()->find($taskId);
        $comment = $task->comments()->find($commentId);
        if ($comment != null && $comment->user_id == auth()->user()->id) {
            $comment->update(
                [
                    'body' => $request->input('body'),
                ]
            );
        }
        return $comment;
    }

    public function deleteComment($project,$taskId,$commentId) 
    {
        $task = $project->tasks()->find($taskId);
        $comment = $task->comments()->find($commentId);
        if ($comment != null && $comment->user_id == auth()->user()->id) {
            $comment->delete();   
        }
    }

    public function deleteTaskFile($project,$taskId,$fileId) 
    {
        try {
            $task = $project->tasks()->find($taskId);
            $file = $task->files()->find($fileId);
            if ($file != null) {
                $url = $file->url;
                FileUploadService::delete('public'.$url);
                $file->delete();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
