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
            if (!Schema::hasColumn('schools', 'subdivision_id')) {
                $table->foreignId('subdivision_id')
                    ->after('id') // Places the column after the 'id' column
                    ->constrained() // Links to the 'id' on the 'subdivisions' table
                    ->onDelete('cascade'); // Deletes schools if the parent subdivision is deleted
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
            if (Schema::hasColumn('schools', 'subdivision_id')) {
                // Drop the foreign key constraint first
                $table->dropForeign(['subdivision_id']);
                // Then drop the column
                $table->dropColumn('subdivision_id');
            }
        });
    }
};
