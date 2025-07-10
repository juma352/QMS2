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
        // 1. Create the new table for program documents
        Schema::create('program_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('document_name');
            $table->string('file_path');
            $table->timestamps();
        });

        // 2. Modify the existing programs table
        Schema::table('programs', function (Blueprint $table) {
            // Add new date columns
            $table->date('license_date')->nullable()->after('program_abbr');
            $table->date('appointment_date')->nullable()->after('license_date');

            // Remove old, now-redundant columns
            $table->dropColumn([
                'role',
                'license_renewal_date',
                'next_approval_date',
                'license_document',
                'approval_document',
                'certificate',
                'other_documents'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop the new documents table
        Schema::dropIfExists('program_documents');

        // 2. Revert changes on the programs table
        Schema::table('programs', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn(['license_date', 'appointment_date']);

            // Add back the old columns
            $table->string('role')->nullable();
            $table->date('license_renewal_date')->nullable();
            $table->date('next_approval_date')->nullable();
            $table->string('license_document')->nullable();
            $table->string('approval_document')->nullable();
            $table->string('certificate')->nullable();
            $table->string('other_documents')->nullable();
        });
    }
};
