<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\CreateRequest;
use App\Http\Requests\Admin\Department\UpdateRequest;
use App\Http\Resources\Admin\Department\DepartmentResource;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentController extends Controller
{
    protected DepartmentRepositoryInterface $repository;

    public function __construct(DepartmentRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return DepartmentResource::collection($this->repository->getAll($request))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): DepartmentResource
    {
        $department = $this->repository->create($request);

        return DepartmentResource::make($department->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): DepartmentResource
    {
        if (!$this->repository->getById($id)) {
            return DepartmentResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        return DepartmentResource::make($this->repository->getById($id))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): DepartmentResource
    {
        if (!$this->repository->getById($id)) {
            return DepartmentResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        $department = $this->repository->update($request,$id);
        return DepartmentResource::make($department->refresh())
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
