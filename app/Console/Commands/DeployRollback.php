<?php

namespace App\Console\Commands;

use App\Models\AuditTrail;
use App\Models\DeploymentLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployRollback extends Command
{
    protected $signature   = 'deploy:rollback {--steps=1 : Number of operation batches to rollback}';
    protected $description = 'Rollback the last deploy operations';

    public function handle(): void
    {
        $steps = (int) $this->option('steps');

        $this->warn("↩️ Rolling back last {$steps} deploy operation batch(es)...");

        // Get last deployment
        $lastDeploy = DeploymentLog::latest()->first();

        try {
            Artisan::call('operations:rollback', ['--step' => $steps]);
            $output = Artisan::output();

            if ($lastDeploy) {
                $lastDeploy->update(['status' => 'failed', 'description' => 'Rolled back via deploy:rollback']);
            }

            AuditTrail::log(
                $lastDeploy?->deployment_name ?? 'unknown',
                'deploy:rollback',
                'success',
                "Rolled back {$steps} step(s)."
            );

            $this->info("✅ Rollback completed.");
            $this->line($output);
        } catch (\Exception $e) {
            AuditTrail::log('rollback', 'deploy:rollback', 'failed', $e->getMessage());
            $this->error("❌ Rollback failed: " . $e->getMessage());
        }
    }
}
