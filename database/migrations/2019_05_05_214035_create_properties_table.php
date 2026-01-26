<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->nullable();
            $table->string('title');
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('city_id')->nullable()->unsigned();
            $table->boolean('is_public')->default(false);
            $table->integer('action_id')->nullable();
            $table->enum('status',['enable','disable','banned','reported'])->default('enable');
            $table->string('website')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('send_message')->default(false);
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('comment')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('show_email')->default(false);
            $table->boolean('show_website')->default(false);
            $table->string('serial_number')->nullable();
            $table->string('google_map')->nullable();

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
        Schema::dropIfExists('properties');
    }
}
