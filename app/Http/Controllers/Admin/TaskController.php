<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\CreateRequest;
use App\Http\Requests\Admin\Task\UpdateRequest;
use App\Http\Resources\Admin\Project\Task\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Http\Resources\Admin\Project\Task\TaskDetailResource;
use App\Http\Requests\Admin\Task\Comment\CommentCreateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Admin\Project\Task\Comment\CommentResource;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Project $project, Request $request): AnonymousResourceCollection
    {
        return TaskResource::collection($this->repository->getAll($project,$request))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request, Project $project): TaskResource
    {
        $task = $this->repository->create($request,$project);

        return TaskResource::make($task->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, $task): TaskDetailResource
    {
        if (!$this->repository->getById($project,$task)) {
            return TaskDetailResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        return TaskDetailResource::make($this->repository->getById($project,$task))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Project $project, $task): TaskResource
    {
        if (!$this->repository->getById($project,$task)) {
            return TaskResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        $task = $this->repository->update($request, $project ,$task);
        return TaskResource::make($task->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, $task): JsonResponse
    {
        if (!$this->repository->getById($project,$task)) {
            return response()->json(null, 404);
        }

        $this->repository->delete($project,$task);
        return response()->json(null, 204);
    }

    public function storeComments(CommentCreateRequest $request,Project $project, $task): CommentResource
    {
        if (!$this->repository->getById($project,$task)) {
            return CommentResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        $comment = $this->repository->createComment($request,$project,$task);
        return CommentResource::make($comment->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    public function updateComments(CommentCreateRequest $request,Project $project, $taskId, $commentId): CommentResource 
    {
        if (!$this->repository->getById($project,$taskId)) {
            return CommentResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }
        $comment = $this->repository->updateComment($request,$project,$taskId,$commentId);
        return CommentResource::make($comment->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.updated'),
            ]);

    }

    public function comments(Project $project, $task): AnonymousResourceCollection 
    {
        return CommentResource::collection($this->repository->getAllComments($project,$task))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    public function destroyComment(Project $project, $taskId, $commentId): JsonResponse 
    {
        if (!$this->repository->getById($project,$taskId)) {
            return response()->json(null, 404);
        }
        $this->repository->deleteComment($project,$taskId,$commentId);
        return response()->json(null, 204);
    }
}
