<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('take_off_sheets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('ahsp_id')->nullable()->constrained('ahsps')->nullOnDelete();
            $table->foreignUuid('job_category_id')->nullable()->constrained('category_jobs')->nullOnDelete();
            $table->string('work_name');
            $table->string('unit');
            $table->string('note')->nullable();
            $table->decimal('volume', 15, 4);
            $table->decimal('locked_unit_price', 15, 2)->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('take_off_sheets');
    }
};
