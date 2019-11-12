<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->bigIncrements('pid');
            $table->string('user_fname');
            $table->string('user_lname')->nullable();
            $table->string('user_mname')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_password');
            $table->date('dob')->nullable();
            $table->string('user_gender')->nullable();
            $table->string('user_mobile',10)->unique();
            $table->string('user_code');
            $table->string('MaritalStatus')->nullable();
            $table->string('user_image')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('face_user_name')->nullable();
            $table->string('user_like')->nullable();
            $table->string('user_interest')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('tbl_user');
    }
}
