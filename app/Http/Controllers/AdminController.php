<?php

namespace App\Http\Controllers;

use App\Models\ApprovalGate;
use App\Models\AuditTrail;
use App\Models\DeploymentLog;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use App\Models\PipelineStep;

class AdminController extends Controller
{
    public function dashboard()
    {
        $total        = DeploymentLog::count();
        $success      = DeploymentLog::where('status', 'success')->count();
        $failed       = DeploymentLog::where('status', 'failed')->count();
        $running      = DeploymentLog::where('status', 'running')->count();
        $successRate  = $total > 0 ? round(($success / $total) * 100, 2) : 0;
        $latestDeployments = DeploymentLog::latest()->take(5)->get();
        $pendingApprovals  = ApprovalGate::where('status', 'pending')->count();
        $activeLocks       = OperationLock::where('is_locked', true)->count();

        return view('admin.dashboard', compact(
            'total', 'success', 'failed', 'running',
            'successRate', 'latestDeployments', 'pendingApprovals', 'activeLocks'
        ));
    }

    public function history()
    {
        $histories = OperationHistory::latest()->paginate(15);
        return view('admin.history', compact('histories'));
    }

    public function pipeline()
    {
        $pipelines = PipelineStep::latest()->get()->groupBy('deployment_name');
        return view('admin.pipeline', compact('pipelines'));
    }

    public function auditTrail()
    {
        $audits = AuditTrail::latest()->paginate(20);
        return view('admin.audit', compact('audits'));
    }
}
