<?php

use App\Models\OperationHistory;
use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Facades\Log;

return new class extends Operation {
    public function __invoke(): void
    {
        Log::info('Deploy operations executed successfully.');

        OperationHistory::create([
            'operation_name' => 'log_deploy',
            'status' => 'success',
            'executed_at' => now()
        ]);

        echo "✅ Deploy logged successfully.\n";
    }

    public function success(): void
    {
        echo "🎉 Operation finished successfully.\n";
    }

    public function failed(): void
    {
        echo "❌ Operation failed!\n";
    }
};