<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sculptures', function (Blueprint $table) {
            $table->string('description_image')->nullable()->after('main_image');
            $table->string('history_image')->nullable()->after('description_image');
        });
    }

    public function down(): void
    {
        Schema::table('sculptures', function (Blueprint $table) {
            $table->dropColumn(['description_image', 'history_image']);
        });
    }
};
