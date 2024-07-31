<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DepartmentController;

require __DIR__.'/auth.php';


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/clients/departments', [ClientController::class, 'departments']);
    
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('clients', ClientController::class);
});