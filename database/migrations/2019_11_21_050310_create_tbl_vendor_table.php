<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tbl_vendor');
        Schema::create('tbl_vendor', function (Blueprint $table) {
            $table->bigIncrements('vendor_id');
            $table->string('vendor_fname')->nullable();
            $table->string('vendor_personaName')->nullable();
            $table->text('vendor_page_screenshot')->nullable();
            $table->string('social_links')->nullable();
            $table->string('vendor_email')->nullable();
            $table->string('mobile_code')->nullable();
            $table->string('vendor_mobile')->nullable();
            $table->string('vendor_login_name')->nullable();
            $table->string('vendor_login_pass')->nullable();
            $table->string('vendor_company_name')->nullable();
            $table->string('seo_url')->nullable();
            $table->string('vendor_slug')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('heading_one')->nullable();
            $table->string('vendor_company_logo')->nullable();
            $table->string('vendor_company_email')->nullable();
            $table->string('vendor_company_mobile')->nullable();
            $table->string('whatsAppNumber')->nullable();
            $table->text('vendor_company_details')->nullable();
            $table->integer('vendor_company_category')->nullable();
            $table->integer('vendor_company_sub_category')->nullable();
            $table->string('vendor_company_city')->nullable();
            $table->string('vendor_company_state')->nullable();
            $table->integer('vendor_company_zip')->nullable();
            $table->string('websiteURL')->nullable();
            $table->text('website_screenshot')->nullable();
            $table->datetime('cron_activation_date')->nullable();
            $table->text('brandBanner')->nullable();
            $table->text('whatsappBannerImg')->nullable();
            $table->text('tabbingImage')->nullable();
            $table->text('brandBannerAllPhotos')->nullable();
            $table->string('country')->nullable();
            $table->integer('dfs_city_id')->nullable();
            $table->integer('dfs_city_loc_id')->nullable();
            $table->integer('mst_id')->nullable();
            //$table->foreign('mst_id')->references('pid')->on('tbl_user');
            $table->integer('trial_or_live')->nullable();
            $table->datetime('trial_end_date')->nullable();
            $table->text('api_popular_keywords')->nullable();
            $table->text('api_average_keywords')->nullable();
            $table->integer('payment_status')->nullable();
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
        Schema::dropIfExists('tbl_vendor');
    }
}
