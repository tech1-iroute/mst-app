<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorLead extends Model
{
    //
    protected $table = 'tbl_vendor_lead';
    protected $primaryKey = 'activity_id';
    protected $fillable = [
        'leadId', 'customer_id', 'name', 'email', 'mobile', 'dob', 'lead_time', 'brand', 'source', 'user', 'connect_mode', 'best_time_to_call', 'sales_executive', 'description', 'query', 'profession', 'status', 'datetime', 'conversiondate', 'checkIn', 'checkOut', 'budget', 'message_send', 'is_deleted', 'oneTimeClickActive', 'lead_status', 'lead_flag', 'lead_importance', 'refered_by', 'status_mobile', 'status_whatsapp', 'status_email', 'leadLink', 'converted_to_user', 'converted_to_customer', 'converted_to_user_date', 'converted_to_customer_date', 'created_at', 'updated_at'
    ];

    public function user(){
         return $this->belongsTo('App\User');
    }

}
