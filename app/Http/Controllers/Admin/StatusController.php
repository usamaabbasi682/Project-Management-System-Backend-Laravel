<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Status\CreateRequest;
use App\Http\Requests\Admin\Status\UpdateRequest;
use App\Repositories\Contracts\StatusRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Admin\Project\Task\Status\StatusResource;
use App\Http\Resources\Admin\Project\Task\Status\StatusOptionResource;

class StatusController extends Controller
{
    protected StatusRepositoryInterface $repository;

    public function __construct(StatusRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return StatusResource::collection($this->repository->getAll($request))
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): StatusResource
    {
        $status = $this->repository->create($request);

        return StatusResource::make($status->refresh())
            ->additional([
                'success' => true,
                'message' => __('messages.created'),
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): StatusResource
    {
        if (!$this->repository->getById($id)) {
            return StatusResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        return StatusResource::make($this->repository->getById($id))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id): StatusResource
    {
        if (!$this->repository->getById($id)) {
            return StatusResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        $status = $this->repository->update($request,$id);
        return StatusResource::make($status->refresh())
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

    public function states(): AnonymousResourceCollection
    {
        $statuses = $this->repository->states();
        return StatusOptionResource::collection($statuses)
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }
}
