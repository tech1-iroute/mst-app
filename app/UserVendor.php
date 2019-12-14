<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVendor extends Model
{
    //
    protected $table = 'tbl_user_vendor';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id', 'user_id', 'vendor_id', 'request_accepted', 'allow_feed', 'type', 'created_at', 'updated_at'
    ];
}
