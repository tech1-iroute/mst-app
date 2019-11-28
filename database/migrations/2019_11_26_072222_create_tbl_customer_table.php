<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->bigIncrements('customer_id');
            $table->string('customer_name')->nullable();
            $table->date('customer_dob')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_email')->nullable();
            $table->integer('customer_type')->nullable();
            $table->integer('business_category')->nullable();
            $table->string('nature_of_uses')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('mst_id')->nullable();
            $table->string('mst_genie')->nullable();
            $table->string('inviteStatus')->nullable();
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
        Schema::dropIfExists('tbl_customer');
    }
}
