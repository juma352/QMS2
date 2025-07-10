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
        // This checks if the table already exists before trying to create it.
        if (!Schema::hasTable('subdivisions')) {
            Schema::create('subdivisions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // e.g., KCHS, GME, CPD, RESEARCH
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdivisions');
    }
};
