<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVendorLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendor_lead', function (Blueprint $table) {
            $table->bigIncrements('leadId');
            $table->integer('customer_id');  
            $table->string('name');
            $table->string('email');  
            $table->string('mobile'); 
            $table->date('dob')->nullable();
            $table->date('lead_time')->nullable();
            $table->integer('brand'); 
            $table->string('source')->nullable();
            $table->integer('user');  
            $table->string('connect_mode')->nullable();     
            $table->string('best_time_to_call')->nullable();   
            $table->string('sales_executive')->nullable();  
            $table->text('description')->nullable();  
            $table->string('query')->nullable();    
            $table->string('profession');    
            $table->integer('status');    
            $table->date('datetime');    
            $table->date('conversiondate');  
            $table->string('checkIn')->nullable();   
            $table->string('checkOut')->nullable();  
            $table->string('budget')->nullable();  
            $table->integer('message_send');     
            $table->integer('is_deleted');   
            $table->integer('oneTimeClickActive');   
            $table->string('lead_status');  
            $table->integer('lead_flag');  
            $table->integer('lead_importance')->nullable(); 
            $table->integer('refered_by')->nullable();  
            $table->integer('status_mobile')->nullable();     
            $table->integer('status_whatsapp')->nullable();   
            $table->integer('status_email')->nullable();     
            $table->string('leadLink');     
            $table->integer('converted_to_user');    
            $table->integer('converted_to_customer');    
            $table->date('converted_to_user_date')->nullable(); 
            $table->date('converted_to_customer_date')->nullable();  
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
        Schema::dropIfExists('tbl_vendor_lead');
    }
}
