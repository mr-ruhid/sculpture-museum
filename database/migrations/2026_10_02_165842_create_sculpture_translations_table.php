<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sculpture_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sculpture_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('history')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->timestamps();

            $table->unique(['sculpture_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sculpture_translations');
    }
};
