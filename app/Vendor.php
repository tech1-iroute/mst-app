<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_vendor';
    protected $primaryKey = 'vendor_id';
    protected $fillable = [
        'vendor_fname', 'vendor_personaName', 'vendor_page_screenshot', 'social_links', 'vendor_email', 'mobile_code', 'vendor_mobile', 'vendor_login_name', 'vendor_login_pass', 'vendor_company_name', 'seo_url', 'vendor_slug', 'seo_title', 'seo_description', 'seo_keywords', 'heading_one', 'vendor_company_logo', 'vendor_company_email', 'vendor_company_mobile', 'whatsAppNumber', 'vendor_company_details', 'vendor_company_category', 'vendor_company_sub_category', 'vendor_company_city', 'vendor_company_state' ,'vendor_company_zip' ,'websiteURL' ,'website_screenshot' ,'brandBanner' ,'whatsappBannerImg' ,'tabbingImage' ,'brandBannerAllPhotos' ,'country' ,'dfs_city_id' ,'dfs_city_loc_id' ,'mst_id' ,'trial_or_live' ,'trial_end_date' ,'api_popular_keywords' ,'api_average_keywords' ,'payment_status' , 'created_at', 'updated_at'
    ];


	public function vendorPosts(){
        return $this->hasMany('App\Post', 'store_id');
    }

    public function user(){
         return $this->belongsTo('App\User');
     }

}
