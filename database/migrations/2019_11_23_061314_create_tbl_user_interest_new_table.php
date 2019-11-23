<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserInterestNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_interest_new', function (Blueprint $table) {
            $table->bigIncrements('interest_id');
            $table->string('interestName');
            $table->integer('status');
            $table->string('icon');
            $table->string('container_class');
            $table->string('color');
            $table->string('interest_new_image');
            $table->string('product_feed_image');
            $table->text('interest_new_description');
            $table->text('seo_url_name');
            $table->string('meta_title');
            $table->text('meta_description');
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
        Schema::dropIfExists('tbl_user_interest_new');
    }
}
