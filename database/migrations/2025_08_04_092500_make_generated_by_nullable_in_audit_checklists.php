<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeGeneratedByNullableInAuditChecklists extends Migration
{
    public function up()
    {
        Schema::table('audit_checklists', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('audit_checklists', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by')->nullable(false)->change();
        });
    }
}
