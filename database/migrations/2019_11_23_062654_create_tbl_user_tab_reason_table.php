<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserTabReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_tab_reason', function (Blueprint $table) {
            $table->bigIncrements('reason_id');
            $table->string('reason_name');
            $table->integer('color');
            $table->string('reason_name_heading');
            $table->integer('category_id');
            $table->integer('interest_id');
            $table->string('icon');
            $table->string('you');
            $table->text('you_1');
            $table->text('you_o');
            $table->string('single');
            $table->text('single_o');
            $table->text('email_template');
            $table->text('email_subject');
            $table->integer('status');
            $table->integer('need_review');
            $table->integer('need_invoicePrice');
            $table->integer('recommend');
            $table->integer('rating');
            $table->string('api_icon_url');
            $table->text('api_icon_url_active');
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
        Schema::dropIfExists('tbl_user_tab_reason');
    }
}
