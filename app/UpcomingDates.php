<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpcomingDates extends Model
{
    //
    protected $table = 'tbl_vendor_events';
    protected $primaryKey = 'id';
    protected $fillable = [
        'event_id', 'event_title', 'event_email_banner', 'event_description', 'voucherCode', 'offer_validity', 'event_reminder1', 'event_reminder2', 'reminder_day_1', 'reminder_day_2', 'start_day', 'start_month', 'start_year', 'end_day', 'end_month', 'end_year', 'start_date', 'end_date', 'vendor_id', 'status', 'showOn', 'type', 'specific_type', 'event_type', 'created_at', 'updated_at'
    ];
}
