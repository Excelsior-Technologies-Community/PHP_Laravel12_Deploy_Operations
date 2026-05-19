
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeploymentLogController;

Route::get('/deployments', [DeploymentLogController::class, 'index']);
Route::post('/deployments', [DeploymentLogController::class, 'store']);
Route::get('/deployments/{id}', [DeploymentLogController::class, 'show']);
Route::put('/deployments/{id}', [DeploymentLogController::class, 'update']);
Route::delete('/deployments/{id}', [DeploymentLogController::class, 'destroy']);