<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ProjectRepositoryInterface
{
    public function getAll(Request $request);
    public function getById($id);
    public function create(Request $request);
    public function suggestStatusColor(string $status);
    public function update(Request $request, $id);
    public function delete($id);
    public function clients();
    public function users();
    public function uploadFile(Request $request, $id);
    public function deleteFile($projectId,$fileId);
}
