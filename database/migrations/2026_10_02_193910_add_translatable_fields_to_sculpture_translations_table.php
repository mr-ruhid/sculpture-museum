<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sculpture_translations', function (Blueprint $table) {
            $table->string('sculptor')->nullable()->after('title');
            $table->string('architect')->nullable()->after('sculptor');
            $table->string('material')->nullable()->after('architect');
            $table->string('style')->nullable()->after('material');
            $table->string('city')->nullable()->after('style');
            $table->string('address')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('sculpture_translations', function (Blueprint $table) {
            $table->dropColumn(['sculptor', 'architect', 'material', 'style', 'city', 'address']);
        });
    }
};
