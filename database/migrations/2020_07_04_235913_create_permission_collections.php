<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.collection_names');

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->string('display_name');
            $table->unique(['name', 'guard_name']);
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('display_name');
            $table->unique(['name', 'guard_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.collection_names');

        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
