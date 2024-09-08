<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getAll(Request $request) 
    {
        $query = Project::query();

        $query->when($request->has('search'), function ($query) use($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        });

        $query->when($request->has('projects') || ($request->has('client') && $request->get('client') != 'all'), function ($query) use($request) {
            $query->where(function ($query) use($request) {
                if($request->has('projects')) {
                    $query->where('client_id', auth('sanctum')->id());
                    if ($request->has('client') && $request->get('client') != 'all') {
                        $query->orWhere('client_id', $request->input('client'));
                    }
                } else {
                    $query->where('client_id', $request->input('client'));
                }
            });
        });

        $query->when($request->has('status') && $request->get('status') != 'all', function ($query) use($request) {
             $query->where('status', $request->input('status'));
        });
        
        $projects = $query->orderBy('id','DESC')->paginate(12);
        return $projects;
    }

    public function getById($id) 
    {
        return  Project::find($id);
    }

    public function create(Request $request) 
    {
        try {
            $project = Project::create(
                $request->safe()->only(['name','prefix','client_id','color',])+['status_color' => '#db5383']
            );

            if (!empty($request->input('users')) && is_array($request->input('users'))) {
                $project->users()->attach($request->input('users'));
            }

            return $project;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function suggestStatusColor(string $status) 
    {
        switch ($status) {
            case 'archived':
                return '#9e9e9e';
            case 'finished':
                return '#4caf50';
            case 'ongoing':
                return '#2196f3';
            default:
                return '#db5383';
        }
    }

    public function update(Request $request, $id) 
    {
        try {
            $project = Project::find($id);

            $status = $request->input('status');
            $statusColor = $this->suggestStatusColor($status);

            $project->update(
                $request->safe()
                ->only(['name','prefix','client','color','budget','budget_type','currency','description','status'])+['status_color' => $statusColor]
            );

            if (!empty($request->input('users')) && is_array($request->input('users'))) {
                $project->users()->sync($request->input('users'));
            }
            return $project;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete($id) 
    {
        $project = Project::findOrFail($id);
        $project->users()->detach();
        $project->files()->delete();
        $project->delete();
    }

    public function clients() {
        $clients = User::select('id','name')->role('client')->orderBy('name','ASC')->get();   
        return $clients;
    }

    public function users() {
        $users = User::select('id','name')->role('user')->orderBy('name','ASC')->get();   
        return $users;
    }

    public function uploadFile(Request $request, $id) {
        try {
            $project = Project::find($id);
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $document = FileUploadService::upload($document, $path = '/public/projects');
                    $project->files()->create(['url' =>  $document->uploaded_path.'/'.$document->uploaded_name]);
                }
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteFile($projectId,$fileId) {
        try {
            $project = Project::find($projectId);
            if ($project->files()->where('id', $fileId)->exists()) {
                $files  = $project->files();
                FileUploadService::delete('public'.$files->firstWhere('id', $fileId)->url);
                $files->where('id', $fileId)->delete();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
