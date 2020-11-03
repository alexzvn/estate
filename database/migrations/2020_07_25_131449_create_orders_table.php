<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('discount')->default(0);
            $table->tinyInteger('discount_type')->default(2);
            $table->integer('price')->default(0);
            $table->integer('after_discount_price')->default(0);
            $table->tinyInteger('status');
            $table->boolean('manual')->default(false);
            $table->tinyInteger('month')->nullable();
            $table->boolean('verified')->default(false);
            $table->foreignId('verifier_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('creator_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('activate_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
