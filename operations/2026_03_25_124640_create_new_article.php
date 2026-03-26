<?php

use App\Models\Article;
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation {
    public function __invoke(): void
    {
        Article::create(['title' => 'New Article via Deploy']);
        echo "✅ New article created via deploy operation.\n";
    }
};