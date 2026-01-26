<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->text('description')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('credit_id')->unsigned()->nullable();
            $table->float('price',8,2)->default(0);
            $table->integer('total_credit')->default(0);
            $table->enum('status',['pending','completed','canceled','refund','processing'])->default('pending');
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
