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
        Schema::table('projects', function (Blueprint $table) {
            Schema::table('projects', function (Blueprint $table) {
                $table->integer('volume')->after('budget_year')->default(0);
                $table->string('unit')->after('volume')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('volume');
                $table->dropColumn('unit');
            });
        });
    }
};
