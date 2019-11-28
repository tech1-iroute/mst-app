<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'tbl_customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name', 'customer_dob', 'customer_address', 'customer_mobile', 'customer_email', 'customer_type', 'business_category', 'nature_of_uses', 'vendor_id', 'mst_id', 'mst_genie', 'inviteStatus', 'created_at', 'updated_at'
    ];
}
