<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahsps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('work_code');
            $table->string('work_name');
            $table->string('unit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahsps');
    }
};
