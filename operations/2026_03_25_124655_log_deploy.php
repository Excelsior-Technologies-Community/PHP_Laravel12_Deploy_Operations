<?php

use App\Models\AuditTrail;
use App\Models\OperationHistory;
use App\Models\OperationLock;
use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

return new class extends Operation {

    public function __invoke(): void
    {
        $name = 'log_deploy';

        if (OperationLock::isLocked($name)) {
            echo "🔒 Operation '{$name}' is already running. Skipping.\n";
            return;
        }

        OperationLock::lock($name);

        try {
            Log::info('Deploy operations executed successfully.', [
                'environment' => config('app.env'),
                'time'        => now()->toDateTimeString(),
            ]);

            OperationHistory::create([
                'operation_name' => $name,
                'status'         => 'success',
                'executed_at'    => now(),
            ]);

            AuditTrail::log($name, 'run', 'success', 'Deploy logged successfully.');

            // Slack Webhook Notification
            $slackUrl = config('services.slack.webhook_url');
            if ($slackUrl) {
                Http::post($slackUrl, [
                    'text' => '🚀 *Deploy Operations* executed successfully on *' . config('app.env') . '* at ' . now()->toDateTimeString(),
                ]);
            }

            echo "✅ Deploy logged successfully.\n";
        } catch (\Exception $e) {
            AuditTrail::log($name, 'run', 'failed', $e->getMessage());
            echo "❌ Failed: " . $e->getMessage() . "\n";
        } finally {
            OperationLock::unlock($name);
        }
    }

    public function success(): void
    {
        // Email Notification on success
        $to = config('mail.from.address');
        if ($to && $to !== 'hello@example.com') {
            Mail::raw('🎉 Deploy operation completed successfully on ' . config('app.env'), function ($msg) use ($to) {
                $msg->to($to)->subject('Deploy Success Notification');
            });
        }
        echo "🎉 Operation finished successfully.\n";
    }

    public function failed(): void
    {
        AuditTrail::log('log_deploy', 'run', 'failed', 'Operation hook reported failure.');
        echo "❌ Operation failed!\n";
    }
};
