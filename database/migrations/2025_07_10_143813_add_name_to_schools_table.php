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
        Schema::table('schools', function (Blueprint $table) {
            // Check if the column does NOT exist before adding it
            if (!Schema::hasColumn('schools', 'name')) {
                // Add the 'name' column after the 'subdivision_id' column
                $table->string('name')->after('subdivision_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Check if the column EXISTS before trying to drop it
            if (Schema::hasColumn('schools', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
