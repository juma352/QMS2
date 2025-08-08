<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPlaceholderToChecklistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            if (!Schema::hasColumn('checklist_items', 'is_placeholder')) {
                $table->boolean('is_placeholder')->default(false)->after('display_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->dropColumn('is_placeholder');
        });
    }
}
