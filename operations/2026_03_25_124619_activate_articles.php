<?php

use App\Models\Article;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {
    public function __invoke(): void
    {
        Article::query()
            ->where('is_active', false)
            ->update(['is_active' => true]);

        echo "✅ All inactive articles are now active.\n";
    }
};