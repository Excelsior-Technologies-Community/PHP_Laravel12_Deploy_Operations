<?php

use App\Models\Article;
use App\Models\AuditTrail;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Facades\DB;

return new class extends Operation {

    public function __invoke(): void
    {
        $name = 'bulk_update_operation';

        if (OperationLock::isLocked($name)) {
            echo "🔒 Operation '{$name}' is already running. Skipping.\n";
            return;
        }

        OperationLock::lock($name);

        try {
            DB::transaction(function () {
                // Bulk update articles: activate all
                $articlesUpdated = Article::where('is_active', false)->count();
                Article::where('is_active', false)->update(['is_active' => true]);

                echo "✅ Bulk Update: {$articlesUpdated} articles activated.\n";
            });

            OperationHistory::create([
                'operation_name' => $name,
                'status'         => 'success',
                'executed_at'    => now(),
            ]);

            AuditTrail::log($name, 'run', 'success', 'Bulk update completed across tables.');

            echo "✅ Bulk update operation completed.\n";
        } catch (\Exception $e) {
            AuditTrail::log($name, 'run', 'failed', $e->getMessage());
            echo "❌ Failed: " . $e->getMessage() . "\n";
        } finally {
            OperationLock::unlock($name);
        }
    }

    public function rollback(): void
    {
        Article::query()->update(['is_active' => false]);
        AuditTrail::log('bulk_update_operation', 'rollback', 'success', 'Bulk update rolled back.');
        echo "↩️ Rollback: Bulk update reversed.\n";
    }
};
