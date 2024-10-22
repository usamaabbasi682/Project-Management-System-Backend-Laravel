<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        $query=User::withCount(['projects','tasks' => function($query) {
            $query->whereHas('status', function ($innerQuery) {
                return $innerQuery->where('name', 'pending');
            });
        }]);

        $query->when(request()->has('search'), function ($query) {
            $query->where('name', 'like', '%' . request()->input('search') . '%');
        });
        
        $query = $query->role(['user','admin'])->paginate(12);

        return $query;
    }

    public function getById($id) {
        return User::find($id);
    }

    public function create(Request $request) {

        $password = $request->input('password');
        $user = User::create([
            'name' => $request->input('name') ?? '',
            'email' => $request->input('email') ?? '',
            'phone' => $request->input('phone') ?? '',
            'salary' => $request->input('salary') ?? '',
            'password' => ($password != null && $password != '') ? Hash::make($password) : null,
            'status' => $request->input('status') ?? '',
        ]);
        $user->assignRole($request->input('role'));

        if ($request->hasFile('profile')) {
            $profile = FileUploadService::upload($request->file('profile'), $path = '/public/users');
            $user->image()->create(['url' =>  $profile->uploaded_path.'/'.$profile->uploaded_name]);
        }

        if (is_array($request->input('projects')) && !empty($request->input('projects'))) {
            $user->projects()->attach($request->input('projects'));
        }

        return $user;
    }

    public function update(Request $request, $id) {
        
        $password = $request->input('password');
        $user = User::find($id);
        $user->update([
            'name' => $request->input('name') ?? '',
            'email' => $request->input('email') ?? '',
            'phone' => $request->input('phone') ?? '',
            'salary' => $request->input('salary') ?? '',
            'password' => ($password != null && $password != '') ? Hash::make($password) : $user->password,
            'status' => $request->input('status') ?? '',
        ]);
        $user->syncRoles($request->input('role'));

        if ($request->hasFile('profile')) {
            if ($user->image) {
                FileUploadService::delete('public'.$user->image->url);
                $user->image->delete();
            }
            $profile = FileUploadService::upload($request->file('profile'), $path = '/public/users');
            $user->image()->create(['url' =>  $profile->uploaded_path.'/'.$profile->uploaded_name]);
        }

        if (is_array($request->input('projects')) && !empty($request->input('projects'))) {
            $user->projects()->sync($request->input('projects'));
        }

        return $user;
    }

    public function delete($id) {
        try {
            $user = User::find($id);
            if ($user) {
                // Delete the user's image if it exists
                if ($user->image) {
                    FileUploadService::delete('public'.$user->image->url);
                    $user->image->delete();
                }
                $user->delete();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
