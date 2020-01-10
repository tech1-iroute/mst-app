<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVendorEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendor_events', function (Blueprint $table) {
            $table->bigIncrements('event_id');
            $table->string('event_title')->nullable();
            $table->text('event_email_banner')->nullable();
            $table->text('event_description')->nullable();
            $table->string('voucherCode')->nullable();
            $table->integer('offer_validity')->nullable();
            $table->date('event_reminder1')->nullable();
            $table->date('event_reminder2')->nullable();
            $table->string('reminder_day_1')->nullable();
            $table->string('reminder_day_2')->nullable();
            $table->string('start_day')->nullable();
            $table->string('start_month')->nullable();
            $table->string('start_year')->nullable();
            $table->string('end_day')->nullable();
            $table->string('end_month')->nullable();
            $table->string('end_year')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('showOn')->nullable();
            $table->string('type')->nullable();
            $table->enum('specific_type', array('birthday','anniversary'));
            $table->enum('event_type', array('event','promotion','festival'));
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
        Schema::dropIfExists('tbl_vendor_events');
    }
}
