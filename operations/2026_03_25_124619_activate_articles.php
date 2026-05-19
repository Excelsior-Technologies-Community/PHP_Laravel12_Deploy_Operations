<?php

use App\Models\Article;
use App\Models\OperationHistory;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {
    public function __invoke(): void
    {
        Article::query()
            ->where('is_active', false)
            ->update(['is_active' => true]);

        OperationHistory::create([
            'operation_name' => 'activate_articles',
            'status' => 'success',
            'executed_at' => now()
        ]);

        echo "✅ All inactive articles are now active.\n";
    }
};