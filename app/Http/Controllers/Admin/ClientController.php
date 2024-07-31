<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Client\CreateRequest;
use App\Http\Requests\Admin\Client\UpdateRequest;
use App\Http\Resources\Admin\User\ClientResource;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Admin\Department\ClientDepartmentResource;

class ClientController extends Controller
{
    protected ClientRepositoryInterface $repository;

    public function __construct(ClientRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return ClientResource::collection($this->repository->getAll($request))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): ClientResource
    {
        $client = $this->repository->create($request);

        return ClientResource::make($client->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ClientResource
    {
        if (!$this->repository->getById($id)) {
            return ClientResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        return ClientResource::make($this->repository->getById($id))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'), 
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): ClientResource
    {
        if (!$this->repository->getById($id)) {
            return ClientResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        $client = $this->repository->update($request,$id);
        return ClientResource::make($client->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$this->repository->getById($id)) {
            return response()->json(null, 404);
        }

        $this->repository->delete($id);
        return response()->json(null, 204);
    }

    public function departments(): AnonymousResourceCollection
    {
        return ClientDepartmentResource::collection($this->repository->departments())
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }
}
