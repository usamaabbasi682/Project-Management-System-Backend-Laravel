<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Repositories\Contracts\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function getAll(Request $request) 
    {
        $query = Department::query();
        $query->when($request->has('search'), function ($query) use($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        });
        $departments = $query->orderBy('id','DESC')->paginate(12);
        return $departments;
    }

    public function getById($id) 
    {
        return  Department::find($id);
    }

    public function create(Request $request) 
    {
        try {
            $department = Department::create(
                $request->safe()->only(['name','description','color'])
            );
            return $department;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $id) 
    {
        try {
            $department = Department::find($id);
            $department->update(
                $request->safe()->only(['name','description','color'])
            );
            return $department;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete($id) 
    {
        $department = Department::findOrFail($id);
        $department->delete();
    }
}
