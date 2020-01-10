<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostClickCount extends Model
{
    //
    protected $table = 'tbl_click_count';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'page_url', 'page_count', 'user_ip', 'created_at', 'updated_at'
    ];
}
