<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftPreference extends Model
{
    //
    protected $table = 'tbl_gift_preferences';
    protected $primaryKey = 'preferences_id';
    protected $fillable = [
        'preferences_name', 'preferences_image', 'status', 'created_at', 'updated_at'
    ];

}
