<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\DeploymentLog;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use App\Models\PipelineStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class OperationApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'operations'   => OperationHistory::latest()->get(),
            'deployments'  => DeploymentLog::latest()->take(10)->get(),
            'audit_trails' => AuditTrail::latest()->take(20)->get(),
        ]);
    }

    public function status(): JsonResponse
    {
        $total   = DeploymentLog::count();
        $success = DeploymentLog::where('status', 'success')->count();
        $failed  = DeploymentLog::where('status', 'failed')->count();
        $running = DeploymentLog::where('status', 'running')->count();

        return response()->json([
            'total'        => $total,
            'success'      => $success,
            'failed'       => $failed,
            'running'      => $running,
            'success_rate' => $total > 0 ? round(($success / $total) * 100, 2) : 0,
            'locks'        => OperationLock::where('is_locked', true)->pluck('operation_name'),
        ]);
    }

    public function run(): JsonResponse
    {
        try {
            Artisan::call('deploy:run');
            return response()->json(['message' => 'Deployment started', 'output' => Artisan::output()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function rollback(): JsonResponse
    {
        try {
            Artisan::call('deploy:rollback');
            return response()->json(['message' => 'Rollback completed', 'output' => Artisan::output()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function pipeline(): JsonResponse
    {
        $steps = PipelineStep::latest()->take(20)->get()->groupBy('deployment_name');
        return response()->json($steps);
    }

    public function auditTrail(): JsonResponse
    {
        return response()->json(AuditTrail::latest()->paginate(20));
    }
}
