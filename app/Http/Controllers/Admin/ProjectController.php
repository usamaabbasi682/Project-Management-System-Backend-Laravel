<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\CreateRequest;
use App\Http\Requests\Admin\Project\UpdateRequest;
use App\Http\Resources\Admin\Project\ProjectResource;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    protected ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return ProjectResource::collection($this->repository->getAll($request))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): ProjectResource
    {
        $project = $this->repository->create($request);

        return ProjectResource::make($project->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ProjectResource
    {
        if (!$this->repository->getById($id)) {
            return ProjectResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        return ProjectResource::make($this->repository->getById($id))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): ProjectResource
    {
        if (!$this->repository->getById($id)) {
            return ProjectResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        $project = $this->repository->update($request,$id);
        return ProjectResource::make($project->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        if (!$this->repository->getById($id)) {
            return response()->json(null, 404);
        }

        $this->repository->delete($id);
        return response()->json(null, 204);
    }
}
