<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45)->unique();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('blocked_until')->nullable();
            $table->timestamp('last_attempt_at')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index('blocked_until');
            $table->index('last_attempt_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
