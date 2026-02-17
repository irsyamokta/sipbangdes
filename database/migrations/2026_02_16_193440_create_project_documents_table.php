<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('progress_id')->nullable()->constrained('project_progresses')->nullOnDelete();
            $table->foreignUuid('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('image_url');
            $table->string('public_id');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
