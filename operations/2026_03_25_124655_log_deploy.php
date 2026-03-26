<?php

use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Facades\Log;

return new class extends Operation {
    public function __invoke(): void
    {
        Log::info('Deploy operations executed successfully.');
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