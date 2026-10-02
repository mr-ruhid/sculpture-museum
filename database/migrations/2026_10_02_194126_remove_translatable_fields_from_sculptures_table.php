<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sculptures', function (Blueprint $table) {
            $table->dropColumn(['sculptor', 'architect', 'material', 'style', 'city', 'address']);
        });
    }

    public function down(): void
    {
        Schema::table('sculptures', function (Blueprint $table) {
            $table->string('sculptor')->nullable();
            $table->string('architect')->nullable();
            $table->string('material')->nullable();
            $table->string('style')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
        });
    }
};
