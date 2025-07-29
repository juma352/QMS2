<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Add new foreign key columns
            $table->foreignId('school_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->foreignId('faculty_member_id')->nullable()->after('school_id')->constrained('staff')->onDelete('set null');

            // Remove old string-based columns
            $table->dropColumn(['subdivision', 'faculty_member']);
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Re-add old columns if we roll back
            $table->string('subdivision')->nullable();
            $table->string('faculty_member')->nullable();

            // Drop foreign key constraints and columns
            $table->dropForeign(['school_id']);
            $table->dropForeign(['faculty_member_id']);
            $table->dropColumn(['school_id', 'faculty_member_id']);
        });
    }
};
