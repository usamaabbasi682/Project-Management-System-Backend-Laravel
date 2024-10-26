<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\CreateRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    protected RoleRepositoryInterface $repository;
    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return RoleResource::collection($this->repository->getAll($request))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): RoleResource
    {
        $role = $this->repository->create($request);

        return RoleResource::make($role->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RoleResource
    {
        if (!$this->repository->getById($id)) {
            return RoleResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        return RoleResource::make($this->repository->getById($id))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id): RoleResource
    {
        if (!$this->repository->getById($id)) {
            return RoleResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        $role = $this->repository->update($request,$id);
        return RoleResource::make($role->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->repository->getById($id)) {
            return response()->json(null, 404);
        }

        $result = $this->repository->delete($id);
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => __('messages.deleted'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('messages.not_deleted'),
            ]);
        }
    }
}
