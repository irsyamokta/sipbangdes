<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('take_off_sheets', function (Blueprint $table) {
            $table->json('locked_snapshot')
                ->nullable()
                ->after('locked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('take_off_sheets', function (Blueprint $table) {
            $table->dropColumn('locked_snapshot');
        });
    }
};
