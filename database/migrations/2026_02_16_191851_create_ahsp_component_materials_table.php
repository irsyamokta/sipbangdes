<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahsp_component_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ahsp_id')->constrained('ahsps')->cascadeOnDelete();
            $table->foreignUuid('material_id')->constrained('master_materials')->cascadeOnDelete();
            $table->decimal('coefficient', 15, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahsp_component_materials');
    }
};
