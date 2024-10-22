<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Resources\Admin\User\UserListsResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Resources\Admin\User\UserListDetailResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return UserListsResource::collection($this->userRepository->getAll())
            ->additional([
                'success' => true,
                'message' => __('messages.fetched'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request):UserListsResource
    {
        $user = $this->userRepository->create($request);
        
        return UserListsResource::make($user->refresh())
        ->additional([
            'success' => true,
            'message' => __('messages.created'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): UserListDetailResource
    {
        if (!$this->userRepository->getById($id)) {
            return UserListDetailResource::make(null)
                ->additional(['success' => false, 'message' =>  __('messages.not_found')]);
        }

        return UserListDetailResource::make($this->userRepository->getById($id))
            ->additional([
                'success' => true,
                'message' => __('messages.retrieved'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id): UserListDetailResource
    {
        if (!$this->userRepository->getById($id)) {
            return UserListDetailResource::make(null)
                ->additional(['success' => false, 'message' => __('messages.not_found')]);
        }

        $user = $this->userRepository->update($request,$id);
        return UserListDetailResource::make($user->refresh())
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
        if (!$this->userRepository->getById($id)) {
            return response()->json(null, 404);
        }
        $result = $this->userRepository->delete($id);
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
