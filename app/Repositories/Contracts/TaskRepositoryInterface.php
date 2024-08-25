<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getAll($project,Request $request);
    public function getById($project,$task);
    public function create(Request $request, $project);
    public function update(Request $request, $project,$taskId);
    public function delete($project,$taskId);
    public function getAllComments($project,$task);
    public function createComment(Request $request,$project, $taskId);
    public function deleteComment($project,$taskId,$commentId);
    public function updateComment(Request $request,$project,$taskId,$commentId);
}
