<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_product';
    protected $primaryKey = 'pid';
    protected $fillable = [
        'prod_name', 'prod_all_img', 'prod_desc', 'prod_status', 'prod_url', 'addDT', 'date', 'uploaded_by_id', 'user_interest', 'showInFeed', 'user_location', 'store_id', 'tab_reason', 'tab_reason_new', 'product_interest_new', 'sub_category', 'post_cat_type', 'post_meta_title', 'post_meta_description', 'product_type', 'user_type', 'feed_to_show', 'customer_feedback', 'anomymous_user', 'schedule_post_date' , 'created_at', 'updated_at'
    ];

    public function user(){
         return $this->belongsTo('App\User');
     }

    public function vendor(){
         return $this->belongsTo('App\User');
     }

    public function postMainCategory(){
        return $this->hasOne('App\MainCategory', 'interest_id');
    }

    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function comments()
    {
        //return $this->hasMany(Comment::class)->whereNull('parent_id');
        return $this->hasMany('App\PostComments', 'cpid');
    }
     
}
