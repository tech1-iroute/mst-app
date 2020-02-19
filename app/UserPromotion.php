<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPromotion extends Model
{
    //
    protected $table = 'tbl_user_promotions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'promotion_id', 'vendor_id', 'user_type', 'promotion_accepted', 'accept_date', 'created_at', 'updated_at'
    ];

    public function user(){
         return $this->belongsTo('App\User');
    }
}
