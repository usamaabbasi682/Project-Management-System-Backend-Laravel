<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Repositories\Contracts\StatusRepositoryInterface;

class StatusRepository implements StatusRepositoryInterface
{
    public function getAll(Request $request)
    {
        $query = Status::orderBy('id', 'desc');

        $query->when($request->has('search'), function ($query) use($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        });
        
        $statuses = $query->paginate(12);
        return $statuses;
    }

    public function getById($id) 
    {
        return Status::find($id);
    }

    public function create(Request $request) 
    {
        $status = Status::create([
            'name' => strtolower($request->name),
            'order' => $request->order,
        ]);
        return $status;
    }

    public function update(Request $request, $id) 
    {
        try {
            $status = Status::find($id);
            $status->update(
                $request->safe()->only(['order'])
            );
            return $status;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete($id) 
    {
        try {
            $status = Status::findOrFail($id);
            $status->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function states() 
    {
        $statuses = Status::selectRaw('name,id')->get();
        return $statuses;
    }
}
