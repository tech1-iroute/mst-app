<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    //
    protected $table = 'tbl_user_interest_new';
    protected $primaryKey = 'interest_id';
    protected $fillable = [
        'interestName', 'status', 'icon', 'container_class', 'color', 'interest_new_image', 'product_feed_image', 'interest_new_description', 'seo_url_name', 'meta_title', 'meta_description', 'created_at', 'updated_at'
    ];

    public function postMainCategory(){
        return $this->hasOne('App\Post', 'product_interest_new');
    }

}
