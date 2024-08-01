<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getAll(Request $request) 
    {
        $query = Project::query();
        $query->when($request->has('search'), function ($query) use($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
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
        $project->delete();
    }
}
