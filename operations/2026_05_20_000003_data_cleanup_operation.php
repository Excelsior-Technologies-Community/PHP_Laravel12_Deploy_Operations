<?php

use App\Models\AuditTrail;
use App\Models\DeploymentLog;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {

    // Only run on production
    public function environment(): array|string
    {
        return ['local', 'production'];
    }

    public function __invoke(): void
    {
        $name = 'data_cleanup_operation';

        if (OperationLock::isLocked($name)) {
            echo "🔒 Operation '{$name}' is already running. Skipping.\n";
            return;
        }

        OperationLock::lock($name);

        try {
            // Delete deployment logs older than 30 days
            $deleted = DeploymentLog::where('created_at', '<', now()->subDays(30))->count();
            DeploymentLog::where('created_at', '<', now()->subDays(30))->delete();

            // Delete old operation histories older than 60 days
            $oldOps = OperationHistory::where('created_at', '<', now()->subDays(60))->count();
            OperationHistory::where('created_at', '<', now()->subDays(60))->delete();

            OperationHistory::create([
                'operation_name' => $name,
                'status'         => 'success',
                'executed_at'    => now(),
            ]);

            AuditTrail::log($name, 'run', 'success', "Cleaned {$deleted} old deployment logs, {$oldOps} old operation histories.");

            echo "✅ Data cleanup: {$deleted} old logs deleted, {$oldOps} old histories deleted.\n";
        } catch (\Exception $e) {
            AuditTrail::log($name, 'run', 'failed', $e->getMessage());
            echo "❌ Failed: " . $e->getMessage() . "\n";
        } finally {
            OperationLock::unlock($name);
        }
    }
};
