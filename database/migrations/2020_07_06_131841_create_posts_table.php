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
            $table->string('phone', 300)->nullable();
            $table->string('commission', 20)->nullable();
            $table->unsignedBigInteger('price')->nullable();

            $table->string('hash', 80)->nullable()->index();
            $table->tinyInteger('status')->default(0)->index();
            $table->string('type')->nullable()->index();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('verifier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('reverser')->default(false);
            $table->boolean('approve_fee')->default(false);

            $table->longText('index_meta')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('publish_at')->nullable()->index();

            $table->index('created_at');
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
