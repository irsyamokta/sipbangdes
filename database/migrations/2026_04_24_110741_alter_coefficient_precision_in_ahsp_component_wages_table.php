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
        Schema::table('ahsp_component_wages', function (Blueprint $table) {
            $table->decimal('coefficient', 18, 8)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ahsp_component_wages', function (Blueprint $table) {
            $table->decimal('coefficient', 15, 4)->change();
        });
    }
};
