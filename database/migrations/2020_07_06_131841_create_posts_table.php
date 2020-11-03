<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('title', 250)->nullable();
            $table->mediumText('content')->nullable();
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('price')->nullable();

            $table->string('hash', 80)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('type')->default('');
            $table->timestamp('publish_at');

            $table->foreignId('user_id')->nullable();
            $table->foreignId('verified_id')->nullable();
            $table->foreignId('province_id')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->foreignId('user_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            fulltext('posts', 'content', 'title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
