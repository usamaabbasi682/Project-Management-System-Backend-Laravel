<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\TaskBoardController;

require __DIR__.'/auth.php';


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/department/options', [ClientController::class, 'departments']);
    Route::get('/tags/options', [TagController::class, 'tags']);   
    Route::get('/status/options',[StatusController::class,'states']); 
    Route::get('/project/options',[ProjectController::class,'projects']); 

    Route::controller(ProjectController::class)->group(function(){
        Route::get('/client/options', 'clients');
        Route::get('/users/options', 'users');
        Route::post('/projects/{project}/file', 'uploadFile');
        Route::delete('/projects/{project}/files/{file}', 'deleteFile');
    });

    Route::controller(TaskController::class)->group(function(){
        Route::get('/projects/{project}/tasks/{task}/comments', 'comments');
        Route::post('/projects/{project}/tasks/{task}/comments', 'storeComments');
        Route::delete('/projects/{project}/tasks/{task}/comments/{comment}/delete', 'destroyComment');
        Route::post('/projects/{project}/tasks/{task}/comments/{comment}/update', 'updateComments');
        Route::delete('/projects/{project}/tasks/{task}/files/{file}/delete', 'deleteTaskFile');
    });

    Route::controller(TaskBoardController::class)->group(function(){
        Route::get('/task-board', 'index');
    });
    
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('projects.tasks', TaskController::class);
    Route::apiResource('statuses', StatusController::class);
});