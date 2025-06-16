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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_type'); // Will store 'Internal' or 'External'
            $table->string('audit_name');
            $table->string('audit_number')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->string('auditor')->nullable();
            $table->foreignId('standard_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status');
            $table->date('date_conducted')->nullable();
            $table->date('next_audit_date')->nullable();
            $table->text('findings')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->string('audit_report_path')->nullable();
            $table->json('supporting_documents_paths')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};