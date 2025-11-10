<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop old 'name' column
            $table->dropColumn('name');

            // Add new columns
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore old 'name' column
            $table->string('name');

            // Drop new columns
            $table->dropColumn(['first_name', 'last_name', 'other_name']);
        });
    }
};
