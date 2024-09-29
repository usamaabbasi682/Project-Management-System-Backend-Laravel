<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Project\Task\TaskBoardResource;
use App\Repositories\Contracts\TaskBoardRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskBoardController extends Controller
{
    protected TaskBoardRepositoryInterface $taskBoardRepository;

    public function __construct(TaskBoardRepositoryInterface $taskBoardRepository) 
    {
        $this->taskBoardRepository = $taskBoardRepository;
    }

    public function index() : AnonymousResourceCollection
    {
        $status = $this->taskBoardRepository->getBoardTasks();
        return TaskBoardResource::collection($status)
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }
}
