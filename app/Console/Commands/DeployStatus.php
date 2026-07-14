<?php

namespace App\Console\Commands;

use App\Models\AuditTrail;
use App\Models\DeploymentLog;
use App\Models\OperationHistory;
use Illuminate\Console\Command;

class DeployStatus extends Command
{
    protected $signature   = 'deploy:status';
    protected $description = 'Show status of all deploy operations and recent deployments';

    public function handle(): void
    {
        $this->info('📊 Deploy Operations Status');
        $this->line(str_repeat('-', 60));

        // Recent Deployments
        $deployments = DeploymentLog::latest()->take(10)->get();

        if ($deployments->isEmpty()) {
            $this->warn('No deployments found.');
        } else {
            $this->table(
                ['ID', 'Name', 'Environment', 'Status', 'Created At'],
                $deployments->map(fn($d) => [
                    $d->id,
                    $d->deployment_name,
                    $d->environment,
                    $d->status,
                    $d->created_at->format('Y-m-d H:i:s'),
                ])
            );
        }

        $this->line('');
        $this->info('📋 Operation Histories');
        $this->line(str_repeat('-', 60));

        $histories = OperationHistory::latest()->take(10)->get();

        if ($histories->isEmpty()) {
            $this->warn('No operation histories found.');
        } else {
            $this->table(
                ['ID', 'Operation', 'Status', 'Executed At'],
                $histories->map(fn($h) => [
                    $h->id,
                    $h->operation_name,
                    $h->status,
                    $h->executed_at,
                ])
            );
        }

        $this->line('');
        $this->info('🔍 Recent Audit Trail');
        $this->line(str_repeat('-', 60));

        $audits = AuditTrail::latest()->take(10)->get();

        if ($audits->isEmpty()) {
            $this->warn('No audit trail found.');
        } else {
            $this->table(
                ['Operation', 'Action', 'By', 'Env', 'Result', 'Time'],
                $audits->map(fn($a) => [
                    $a->operation_name,
                    $a->action,
                    $a->performed_by,
                    $a->environment,
                    $a->result,
                    $a->created_at->format('Y-m-d H:i:s'),
                ])
            );
        }
    }
}
