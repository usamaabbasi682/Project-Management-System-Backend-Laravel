<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function getAll(Request $request) 
    {
        $query = User::query();
        $query->when($request->has('search'), function ($query) use($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        });
        $query->when($request->has('department') && $request->get('department') != null, function ($query) use($request) {
            $query->where('department_id', $request->input('department'));
        });
        $clients = $query->role('client')->orderBy('id','DESC')->paginate(12);
        return $clients;
    }

    public function getById($id) 
    {
        return  User::find($id);
    }

    public function create(Request $request) 
    {
        try {
            $password = ($request->input('password') != '' && $request->input('client_panel') == 1) ? Hash::make($request->input('password')) : null;
            $client = User::create([
                'department_id' => $request->input('department'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'password' => $password,
                'status' => '1',
            ]);
            $client->assignRole('client');

            if ($request->hasFile('profile')) {
                $profile = FileUploadService::upload($request->file('profile'), $path = '/public/clients');
                $client->image()->create(['url' =>  $profile->uploaded_path.'/'.$profile->uploaded_name]);
            }
            
            return $client;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $id) 
    {
        try {
            $client = User::find($id);
            $password = $request->input('password') != '' ? Hash::make($request->input('password')) : $client->password;
            $client->update([
                'department_id' => $request->input('department'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'password' => $password,
            ]);

            if ($request->hasFile('profile')) {
                if($client->image != null) {
                    FileUploadService::delete('public'.$client->image->url);
                    $client->image->delete();
                }
                $profile = FileUploadService::upload($request->file('profile'), $path = '/public/clients');
                $client->image()->create(['url' =>  $profile->uploaded_path.'/'.$profile->uploaded_name]);
            }

            return $client;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete($id) 
    {
        $client = User::findOrFail($id);
        FileUploadService::delete('public'.$client->image->url);
        $client->image->delete();
        $client->delete();
    }

    public function departments() {
        $departments = Department::orderBy('name','ASC')->get();
        return $departments;
    }
}
