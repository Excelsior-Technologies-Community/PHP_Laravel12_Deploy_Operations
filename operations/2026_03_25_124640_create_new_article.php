<?php

use App\Models\Article;
use App\Models\OperationHistory;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {
    public function __invoke(): void
    {
        Article::create([
            'title' => 'New Article via Deploy'
        ]);

        OperationHistory::create([
            'operation_name' => 'create_new_article',
            'status' => 'success',
            'executed_at' => now()
        ]);

        echo "✅ New article created via deploy operation.\n";
    }
};