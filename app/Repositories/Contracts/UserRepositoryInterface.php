<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(Request $request);
    public function update(Request $request, $id);
    public function delete($id);
}
