<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->string('operation_name');
            $table->string('action'); // run, rollback, approve, reject
            $table->string('performed_by')->default('system');
            $table->enum('environment', ['local', 'staging', 'production'])->default('local');
            $table->enum('result', ['success', 'failed', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
