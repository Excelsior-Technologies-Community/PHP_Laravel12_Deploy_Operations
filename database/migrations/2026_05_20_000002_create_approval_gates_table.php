<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approval_gates', function (Blueprint $table) {
            $table->id();
            $table->string('operation_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('requested_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_gates');
    }
};
