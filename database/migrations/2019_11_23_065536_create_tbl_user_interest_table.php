<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserInterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_interest', function (Blueprint $table) {
            $table->bigIncrements('interest_id');
            $table->string('interest_name');
            $table->string('interest_image');
            $table->string('status');
            $table->integer('parentId');
            $table->integer('sub_category_id');
            $table->string('icon');
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
        Schema::dropIfExists('tbl_user_interest');
    }
}
