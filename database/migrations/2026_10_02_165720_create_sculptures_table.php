<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sculptures', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('sculptor')->nullable();
            $table->string('architect')->nullable();
            $table->year('year')->nullable();
            $table->date('opening_date')->nullable();
            $table->string('material')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('style')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('condition')->default('exists');
            $table->text('registration_info')->nullable();
            $table->string('main_image')->nullable();
            $table->text('panorama_embed')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sculptures');
    }
};
