<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tbl_product');
        Schema::create('tbl_product', function (Blueprint $table) {
            $table->bigIncrements('pid');
            $table->string('prod_name');
            $table->string('prod_all_img')->nullable();
            $table->text('prod_desc')->nullable();
            $table->enum('prod_status', array('A','D','B','W','CPF','S','SP'));
            $table->string('prod_url')->nullable();
            $table->date('addDT')->nullable();
            $table->string('date')->nullable();
            $table->integer('uploaded_by_id');
            //$table->foreign('uploaded_by_id')->references('pid')->on('tbl_user');
            $table->integer('user_interest')->nullable();
            $table->integer('showInFeed')->nullable();
            $table->string('user_location')->nullable();
            $table->integer('store_id')->nullable();
            $table->string('tab_reason')->nullable();
            $table->integer('tab_reason_new')->nullable();
            $table->integer('product_interest_new')->nullable();
            $table->integer('sub_category')->nullable();
            $table->string('post_cat_type')->nullable();
            $table->string('post_meta_title')->nullable();
            $table->string('post_meta_description')->nullable();
            $table->string('product_type')->nullable();
            $table->enum('user_type', array('user','customer','lead'));
            $table->enum('feed_to_show', array('all','customer','lead'));
            $table->string('customer_feedback')->nullable();
            $table->enum('anomymous_user', array('yes','no'));
            $table->string('schedule_post_date')->nullable();
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
        Schema::dropIfExists('tbl_product');
    }
}
