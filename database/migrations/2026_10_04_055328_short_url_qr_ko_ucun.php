<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('target_type', 32);
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('target_params')->nullable();
            $table->string('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamps();

            $table->index('target_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
