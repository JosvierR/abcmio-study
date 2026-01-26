<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToPropertiesTable extends Migration
{
    protected $fields = ['id','title', 'user_id', 'category_id', 'country_id'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries');
            $table->index($this->fields);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign('country_id');
            $table->dropIndex($this->fields);
        });
    }
}
