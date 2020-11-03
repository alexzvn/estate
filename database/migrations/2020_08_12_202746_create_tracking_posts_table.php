<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_posts', function (Blueprint $table) {
            $table->string('phone', 50)->primary();

            $table->unsignedInteger('district_unique')->default(0);
            $table->unsignedInteger('categories_unique')->default(0);
            $table->unsignedInteger('seen')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_posts');
    }
}
