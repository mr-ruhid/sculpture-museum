<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sculptures', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('is_published');
            $table->index('sort_order');
        });

        DB::table('sculptures')
            ->orderBy('created_at')
            ->get()
            ->each(function ($sculpture, $index) {
                DB::table('sculptures')
                    ->where('id', $sculpture->id)
                    ->update(['sort_order' => $index + 1]);
            });
    }

    public function down(): void
    {
        Schema::table('sculptures', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropColumn('sort_order');
        });
    }
};
