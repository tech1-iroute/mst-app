<?php

namespace App\Http\Controllers\api\v1\UpcomingDates;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Vendor; 
use App\UpcomingDates; 
use App\UserVendor;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;
use Carbon\Carbon;

class UpcomingDatesController extends Controller
{
	public $successStatus = 200;
    public function show(){

    	$arr = array();
    	$upcomingDatesArray = array();
	    $user_id    = Auth::id();
	    $userVendor = UserVendor::where('user_id','=',$user_id)->get(['vendor_id'])->toArray();
	      //print_r($userVendor); die;
	      foreach($userVendor as $value){
	         $vendorIds[] = $value['vendor_id'];     
	      }
	    $upcomingDatesDetails = UpcomingDates::whereIn('vendor_id',$vendorIds)->whereDate('start_date','>=',Carbon::now())->get();
	    $upcomingDatesCount = count($upcomingDatesDetails);

	    foreach($upcomingDatesDetails as $upcomingDatesValue){
	    	$arr['event_title'] = $upcomingDatesValue->event_title;
	    	$arr['voucherCode'] = $upcomingDatesValue->voucherCode;
	    	$arr['event_description'] = $upcomingDatesValue->event_description;
	    	$arr['start_date'] = $upcomingDatesValue->start_date;
	    	$arr['end_date'] = $upcomingDatesValue->end_date;
	    	$arr['event_id'] = $upcomingDatesValue->event_id;
	    	$arr['vendorDetail'] = Vendor::where('vendor_id','=',$upcomingDatesValue->vendor_id)->get(['vendor_id','seo_url','vendor_slug','websiteURL','vendor_company_logo','vendor_fname','vendor_company_details','vendor_company_name']);
	    	$upcomingDatesArray[]=$arr;
	    }

	    $response_array['data']=array('upcoming_dates_details'=>$upcomingDatesArray);
	    return response()->json(['response' => $response_array], $this-> successStatus);
	}
}
