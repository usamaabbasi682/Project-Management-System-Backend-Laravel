<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\DepartmentController;

require __DIR__.'/auth.php';


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/department/options', [ClientController::class, 'departments']);
    Route::get('/client/options', [ProjectController::class, 'clients']);
    Route::get('/users/options', [ProjectController::class, 'users']);
    Route::post('/projects/{project}/file', [ProjectController::class, 'uploadFile']);
    Route::delete('/projects/{project}/files/{file}', [ProjectController::class, 'deleteFile']);
    
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
});