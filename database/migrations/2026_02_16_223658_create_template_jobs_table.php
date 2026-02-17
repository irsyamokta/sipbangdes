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
        Schema::create('template_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->constrained('category_jobs')->restrictOnDelete();
            $table->foreignUuid('ahsp_id')->constrained('ahsps')->restrictOnDelete();
            $table->string('work_name');
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_jobs');
    }
};
