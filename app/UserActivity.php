<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    //
    protected $table = 'tbl_user_activity';
    protected $primaryKey = 'activity_id';
    protected $fillable = [
        'reason_id', 'product_id', 'user_id', 'activity_date', 'created_at', 'updated_at'
    ];

}
