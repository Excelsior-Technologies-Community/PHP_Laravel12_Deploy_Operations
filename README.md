# PHP_Laravel12_Deploy_Operations

A Laravel 12 project demonstrating deployment automation using Deploy Operations.



## Project Description

PHP_Laravel12_Deploy_Operations is a Laravel 12-based application that demonstrates how to manage and execute deployment-related tasks using the Deploy Operations package.

It allows developers to create custom operations that run automatically during deployment, such as updating database records, inserting new data, or logging deployment activity. This helps in automating repetitive tasks and maintaining consistency across deployments.

This project follows best practices for deployment automation and is useful for real-world production environments.



## Key Features

- Create custom deploy operations using Artisan commands
- Execute operations only once (like migrations)
- Automatically track executed operations in the database
- Support rollback functionality for operations
- Add success and failure hooks for better control
- Organize deployment logic in a clean and structured way
- Reduce manual work during deployment



## Operations Tracking

The package automatically creates a table in the database to track executed operations.

This ensures:
- Each operation runs only once
- Operations are not repeated
- Rollback functionality works correctly



## Technology Stack

- Backend Framework: Laravel 12
- Language: PHP 8.x
- Database: MySQL
- Package Used: dragon-code/laravel-deploy-operations
- Tools: Composer, Artisan CLI, XAMPP


---


## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Deploy_Operations "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Deploy_Operations

```

#### Explanation:

Creates a fresh Laravel 12 project using Composer and moves into the project directory to start development.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Deploy_Operations
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Deploy_Operations

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel to MySQL and creates default tables like users, jobs, etc., using migrations.





## STEP 3: Install Deploy Operations Package

### Run:

```
composer require dragon-code/laravel-deploy-operations

```

### Publish the config:

```
php artisan vendor:publish --tag=deploy-operations

```

### This creates:

```
config/operations.php

```

#### Explanation:

Installs the Deploy Operations package and publishes its configuration file to control how operations work.





## STEP 4: Create Example Database Table

### We’ll create a simple articles table for demonstration.

```
php artisan make:migration create_articles_table --create=articles

```

### Edit migration database/migrations/xxxx_create_articles_table.php:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

```

### Then Run:

```
php artisan migrate

```

### Seed some dummy articles:

```
php artisan tinker
>>> \App\Models\Article::create(['title' => 'Article 1']);
>>> \App\Models\Article::create(['title' => 'Article 2']);
>>> exit

```

#### Explanation:

Creates an articles table and inserts sample data to test deploy operations.




## STEP 5: Create Model

### Run:

```
php artisan make:model Article

```


### Model File: app/Models/Article.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'is_active'];
}

```

#### Explanation:

Creates the Article model to interact with the articles table and allows mass assignment for title and is_active.





## STEP 6: Create Deploy Operations


#### We will create 3 example operations.


### STEP 6.1: Activate Inactive Articles  

#### Run: 

```
php artisan make:operation activate_articles

```

#### Edit operations/xxxx_activate_articles.php:

```
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

```

#### Explanation:

Updates all inactive articles to active when the operation runs.




### STEP 6.2: Create a New Article  

#### Run: 

```
php artisan make:operation create_new_article

```

#### Edit operations/xxxx_create_new_article.php:

```
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

```

#### Explanation:

Creates a new article automatically during deployment.




### STEP 6.3: Log Operation Success  

#### Run: 

```
php artisan make:operation log_deploy

```

#### Edit operations/xxxx_log_deploy.php:

```
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

```

#### Explanation:

Logs deployment activity and shows success or failure messages after execution.




## STEP 7: Run Deploy Operations

### Execute all operations:

```
php artisan operations

```


### Expected Terminal Output (Console)

```
✅ All inactive articles are now active.
✅ New article created via deploy operation.
✅ Deploy logged successfully.
🎉 Operation finished successfully.

```

#### Explanation:

Runs all pending operations once and records them in the database to prevent re-execution.



### Expected Output Screenshot:


<img src="screenshots/Screenshot 2026-03-26 110053.png" width="900">


---


## Project Folder Structure:

```
PHP_Laravel12_Deploy_Operations/
│
├── app/
│   ├── Models/
│   │   └── Article.php
│   └── Http/
│       └── Controllers/
│
├── config/
│   └── operations.php
│
├── database/
│   ├── migrations/
│   │   ├── 2026_03_25_000000_create_users_table.php
│   │   ├── 2026_03_25_000001_create_jobs_table.php
│   │   └── 2026_03_25_000002_create_articles_table.php
│   └── seeders/
│
├── operations/    # Deploy Operations Folder
│   ├── 2026_03_25_124619_activate_articles.php
│   ├── 2026_03_25_124640_create_new_article.php
│   └── 2026_03_25_124655_log_deploy.php
│
├── routes/
│   └── web.php
│
├── resources/
│   └── views/
│
├── storage/
│   └── logs/
│
├── bootstrap/
├── public/
├── vendor/
│
├── .env
├── artisan
├── composer.json
└── README.md

```
