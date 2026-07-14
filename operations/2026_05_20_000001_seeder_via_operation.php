<?php

use App\Models\Article;
use App\Models\AuditTrail;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {

    // Only run on local & staging
    public function environment(): array|string
    {
        return ['local', 'staging'];
    }

    public function __invoke(): void
    {
        $name = 'seeder_via_operation';

        if (OperationLock::isLocked($name)) {
            echo "🔒 Operation '{$name}' is already running. Skipping.\n";
            return;
        }

        OperationLock::lock($name);

        try {
            $seeds = [
                ['title' => 'Seeded Article: Laravel 12 Features',    'is_active' => true],
                ['title' => 'Seeded Article: Deploy Operations Guide', 'is_active' => true],
                ['title' => 'Seeded Article: PHP 8.3 Updates',        'is_active' => false],
            ];

            foreach ($seeds as $seed) {
                Article::firstOrCreate(['title' => $seed['title']], $seed);
            }

            OperationHistory::create([
                'operation_name' => $name,
                'status'         => 'success',
                'executed_at'    => now(),
            ]);

            AuditTrail::log($name, 'run', 'success', '3 seed articles inserted.');

            echo "✅ Seeder via operation: 3 articles seeded.\n";
        } catch (\Exception $e) {
            AuditTrail::log($name, 'run', 'failed', $e->getMessage());
            echo "❌ Failed: " . $e->getMessage() . "\n";
        } finally {
            OperationLock::unlock($name);
        }
    }

    public function rollback(): void
    {
        Article::whereIn('title', [
            'Seeded Article: Laravel 12 Features',
            'Seeded Article: Deploy Operations Guide',
            'Seeded Article: PHP 8.3 Updates',
        ])->delete();

        AuditTrail::log('seeder_via_operation', 'rollback', 'success', 'Seeded articles deleted.');
        echo "↩️ Rollback: Seeded articles deleted.\n";
    }
};
