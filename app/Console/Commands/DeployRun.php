<?php

namespace App\Console\Commands;

use App\Models\AuditTrail;
use App\Models\DeploymentLog;
use App\Models\PipelineStep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployRun extends Command
{
    protected $signature   = 'deploy:run {--env= : Target environment (local/staging/production)}';
    protected $description = 'Run all pending deploy operations with pipeline tracking';

    public function handle(): void
    {
        $env = $this->option('env') ?? config('app.env');

        $this->info("🚀 Starting deployment on [{$env}]...");

        $deploymentName = 'deploy_' . now()->format('Ymd_His');

        // Create deployment log
        $log = DeploymentLog::create([
            'deployment_name' => $deploymentName,
            'environment'     => in_array($env, ['local', 'staging', 'production']) ? $env : 'local',
            'status'          => 'running',
            'description'     => 'Triggered via deploy:run command',
        ]);

        // Define pipeline steps
        $steps = [
            ['step_order' => 1, 'step_name' => 'Cache Clear'],
            ['step_order' => 2, 'step_name' => 'Run Migrations'],
            ['step_order' => 3, 'step_name' => 'Run Deploy Operations'],
            ['step_order' => 4, 'step_name' => 'Optimize Application'],
        ];

        foreach ($steps as $step) {
            PipelineStep::create(array_merge($step, [
                'deployment_name' => $deploymentName,
                'status'          => 'pending',
            ]));
        }

        try {
            // Step 1: Cache Clear
            $this->runStep($deploymentName, 1, 'Cache Clear', fn() => Artisan::call('cache:clear'));

            // Step 2: Migrations
            $this->runStep($deploymentName, 2, 'Run Migrations', fn() => Artisan::call('migrate', ['--force' => true]));

            // Step 3: Deploy Operations
            $this->runStep($deploymentName, 3, 'Run Deploy Operations', fn() => Artisan::call('operations'));

            // Step 4: Optimize
            $this->runStep($deploymentName, 4, 'Optimize Application', fn() => Artisan::call('optimize'));

            $log->update(['status' => 'success']);
            AuditTrail::log($deploymentName, 'deploy:run', 'success', 'Full deployment completed.');
            $this->info("✅ Deployment [{$deploymentName}] completed successfully!");
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'description' => $e->getMessage()]);
            AuditTrail::log($deploymentName, 'deploy:run', 'failed', $e->getMessage());
            $this->error("❌ Deployment failed: " . $e->getMessage());
        }
    }

    private function runStep(string $deployment, int $order, string $name, callable $action): void
    {
        $step = PipelineStep::where('deployment_name', $deployment)->where('step_order', $order)->first();
        $step?->update(['status' => 'running', 'started_at' => now()]);

        $this->line("  ▶ Running: {$name}...");

        try {
            $action();
            $step?->update(['status' => 'success', 'completed_at' => now(), 'output' => 'Completed successfully']);
            $this->line("  ✅ {$name} done.");
        } catch (\Exception $e) {
            $step?->update(['status' => 'failed', 'completed_at' => now(), 'output' => $e->getMessage()]);
            throw $e;
        }
    }
}
