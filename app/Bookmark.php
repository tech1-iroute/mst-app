<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    //
    protected $table = 'tbl_bookmark';
    protected $primaryKey = 'pid';
    protected $fillable = [
        'user_id', 'bookmark_dt', 'bookmark_type', 'brandID', 'interest_id', 'category_id', 'created_at', 'updated_at'
    ];
}
