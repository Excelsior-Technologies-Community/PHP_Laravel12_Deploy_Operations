<?php

use App\Models\ApprovalGate;
use App\Models\Article;
use App\Models\AuditTrail;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {

    // Dependency: activate_articles must have run first
    public function __invoke(): void
    {
        $name = 'create_new_article';

        // Operation Locking
        if (OperationLock::isLocked($name)) {
            echo "🔒 Operation '{$name}' is already running. Skipping.\n";
            return;
        }

        // Approval Gate: check if approved
        if (!ApprovalGate::isApproved($name)) {
            // Auto-create pending approval if not exists
            if (!ApprovalGate::isPending($name)) {
                ApprovalGate::create([
                    'operation_name' => $name,
                    'status'         => 'pending',
                    'requested_by'   => 'system',
                ]);
            }
            echo "⏳ Operation '{$name}' is waiting for approval. Visit /admin/approvals to approve.\n";
            return;
        }

        OperationLock::lock($name);

        try {
            // Dependency check: activate_articles must have run
            $depRan = OperationHistory::where('operation_name', 'activate_articles')
                ->where('status', 'success')->exists();

            if (!$depRan) {
                echo "⚠️ Dependency 'activate_articles' has not run yet. Skipping.\n";
                return;
            }

            Article::create(['title' => 'New Article via Deploy Operation']);

            OperationHistory::create([
                'operation_name' => $name,
                'status'         => 'success',
                'executed_at'    => now(),
            ]);

            AuditTrail::log($name, 'run', 'success', 'New article created via deploy.');

            echo "✅ New article created via deploy operation.\n";
        } catch (\Exception $e) {
            AuditTrail::log($name, 'run', 'failed', $e->getMessage());
            echo "❌ Failed: " . $e->getMessage() . "\n";
        } finally {
            OperationLock::unlock($name);
        }
    }

    public function rollback(): void
    {
        Article::where('title', 'New Article via Deploy Operation')->delete();
        AuditTrail::log('create_new_article', 'rollback', 'success', 'Rolled back: article deleted.');
        echo "↩️ Rollback: New article deleted.\n";
    }
};
