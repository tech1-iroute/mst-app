<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    //
    protected $table = 'tbl_user_interest';
    protected $primaryKey = 'interest_id';
    protected $fillable = [
        'interest_name', 'interest_image', 'status', 'parentId', 'sub_category_id', 'icon', 'created_at', 'updated_at'
    ];
}
