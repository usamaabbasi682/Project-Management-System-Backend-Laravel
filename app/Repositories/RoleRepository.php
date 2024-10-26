<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAll(Request $request) {
        $roles = Role::orderBy('name', 'asc');

        $roles->when($request->has('search'),function($query) use($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        });

        $roles = $roles->paginate(10);
        return $roles;
    }

    public function getById($id) {
        return Role::find($id);
    }

    public function create(Request $request) {
        try {
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'web',
            ]);
            // $role->syncPermissions($request->permissions);
            return $role;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $id) {
        try {
            $role = Role::find($id);
            $role->update($request->safe()->only(['name']));
            // $role->syncPermissions($request->permissions);
            return $role;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete($id) {
        try {
            $role = Role::find($id);
            $role->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
