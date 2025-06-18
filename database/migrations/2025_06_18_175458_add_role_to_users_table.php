<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method is executed when you run `php artisan migrate`.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add a 'role' column. It will store text like 'admin' or 'user'.
            // We'll set a default value of 'user' for any new registrations.
            // The 'after('email')' part is optional, it just places the column neatly in the database table structure.
            $table->string('role')->default('user')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     * This method is executed when you run `php artisan migrate:rollback`.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This defines how to remove the column if we ever need to undo the migration.
            $table->dropColumn('role');
        });
    }
};
