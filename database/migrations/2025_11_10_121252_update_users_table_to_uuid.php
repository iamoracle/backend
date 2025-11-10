<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Ramsey\Uuid\Uuid;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Add a temporary UUID column as first column
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid_tmp')->nullable()->first();
        });

        // Step 2: Fill the temporary UUID column with new UUIDs
        User::all()->each(function ($user) {
            $user->uuid_tmp = Uuid::uuid4()->toString();
            $user->save();
        });

        // Step 3: Drop old primary key and old id column
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary('users_pkey'); // Postgres default primary key
            $table->dropColumn('id');
        });

        // Step 4: Make the temporary UUID column primary
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid_tmp')->primary()->change();
        });

        // Step 5: Rename temporary UUID column to 'id'
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('uuid_tmp', 'id');
        });
    }

    public function down(): void
    {
        // Step 1: Drop current UUID primary key
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary('users_pkey');
        });

        // Step 2: Add a temporary bigint column as first column and make it primary
        Schema::table('users', function (Blueprint $table) {
            $table->bigIncrements('id_tmp')->first(); // automatically primary key
        });

        // Step 3: Drop UUID column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id'); // drop UUID primary key
        });

        // Step 4: Rename temporary bigint column back to 'id'
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id_tmp', 'id');
        });
    }
};
