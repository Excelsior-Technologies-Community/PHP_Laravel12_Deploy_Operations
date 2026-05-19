<?php

namespace App\Http\Controllers;

use App\Models\DeploymentLog;

class DashboardController extends Controller
{
    public function index()
    {
        $total = DeploymentLog::count();

        $success = DeploymentLog::where(
            'status',
            'success'
        )->count();

        $failed = DeploymentLog::where(
            'status',
            'failed'
        )->count();

        $running = DeploymentLog::where(
            'status',
            'running'
        )->count();

        $successRate = $total > 0
            ? round(($success/$total)*100,2)
            : 0;

        $latestDeployments =
            DeploymentLog::latest()
            ->take(5)
            ->get();

        return view(
            'dashboard',
            compact(
                'total',
                'success',
                'failed',
                'running',
                'successRate',
                'latestDeployments'
            )
        );
    }
}