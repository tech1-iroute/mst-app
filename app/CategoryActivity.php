<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryActivity extends Model
{
    //
    protected $table = 'tbl_user_tab_reason';
    protected $primaryKey = 'reason_id';
    protected $fillable = [
        'reason_name', 'color', 'reason_name_heading', 'category_id', 'interest_id', 'icon', 'you', 'you_1', 'you_o', 'single', 'single_o', 'email_template', 'email_subject', 'status', 'need_review', 'need_invoicePrice', 'recommend', 'rating', 'api_icon_url', 'api_icon_url_active', 'created_at', 'updated_at'
    ];
}
