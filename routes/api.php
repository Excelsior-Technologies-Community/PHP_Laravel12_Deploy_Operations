<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeploymentLogController;
use App\Http\Controllers\OperationApiController;

// Deployment Logs CRUD API
Route::apiResource('deployments', DeploymentLogController::class);

// Operations API
Route::prefix('operations')->name('operations.')->group(function () {
    Route::get('/',          [OperationApiController::class, 'index'])->name('index');
    Route::get('/status',    [OperationApiController::class, 'status'])->name('status');
    Route::get('/pipeline',  [OperationApiController::class, 'pipeline'])->name('pipeline');
    Route::get('/audit',     [OperationApiController::class, 'auditTrail'])->name('audit');
    Route::post('/run',      [OperationApiController::class, 'run'])->name('run');
    Route::post('/rollback', [OperationApiController::class, 'rollback'])->name('rollback');
});
