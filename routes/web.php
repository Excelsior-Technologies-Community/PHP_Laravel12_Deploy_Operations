<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DeploymentLogController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Web Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',   [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/history',     [AdminController::class, 'history'])->name('history');
    Route::get('/pipeline',    [AdminController::class, 'pipeline'])->name('pipeline');
    Route::get('/audit',       [AdminController::class, 'auditTrail'])->name('audit');
    Route::get('/deployments', [DeploymentLogController::class, 'webIndex'])->name('deployments');

    // Approval Gates
    Route::get('/approvals',          [ApprovalController::class, 'index'])->name('approvals');
    Route::post('/approvals',         [ApprovalController::class, 'store'])->name('approvals.store');
    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject',  [ApprovalController::class, 'reject'])->name('approvals.reject');
});
