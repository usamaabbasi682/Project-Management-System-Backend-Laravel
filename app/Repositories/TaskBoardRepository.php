<?php

namespace App\Repositories;

use App\Models\Status;
use App\Repositories\Contracts\TaskBoardRepositoryInterface;

class TaskBoardRepository implements TaskBoardRepositoryInterface
{
    public function getBoardTasks()
    {
        $status = Status::with('tasks')->get();   
        return $status;
    }
}
