<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBookmarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bookmark', function (Blueprint $table) {
            $table->bigIncrements('pid');
            $table->integer('user_id');
            $table->datetime('bookmark_dt');
            $table->enum('bookmark_type', array('I','E'));
            $table->integer('brandID');
            $table->integer('interest_id');
            $table->integer('category_id');
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
        Schema::dropIfExists('tbl_bookmark');
    }
}
