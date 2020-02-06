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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;

class UpcomingDatesController extends Controller
{
	use AuthenticatesUsers;
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
	    $upcomingDatesDetails = UpcomingDates::whereIn('vendor_id',$vendorIds)->whereDate('end_date','>=',Carbon::now())->get();
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

	public function lapsedUpcomingDateShow(){

    	$arr = array();
    	$lapsedDatesArray = array();
	    $user_id    = Auth::id();
	    $userVendor = UserVendor::where('user_id','=',$user_id)->get(['vendor_id'])->toArray();
	      //print_r($userVendor); die;
	      foreach($userVendor as $value){
	         $vendorIds[] = $value['vendor_id'];     
	      }
	    $lapsedDatesDetails = UpcomingDates::whereIn('vendor_id',$vendorIds)->whereDate('end_date','<',Carbon::now())->get();
	    $lapsedDatesCount = count($lapsedDatesDetails);

	    foreach($lapsedDatesDetails as $lapsedDatesValue){
	    	$arr['event_title'] = $lapsedDatesValue->event_title;
	    	$arr['voucherCode'] = $lapsedDatesValue->voucherCode;
	    	$arr['event_description'] = $lapsedDatesValue->event_description;
	    	$arr['start_date'] = $lapsedDatesValue->start_date;
	    	$arr['end_date'] = $lapsedDatesValue->end_date;
	    	$arr['event_id'] = $lapsedDatesValue->event_id;
	    	$arr['vendorDetail'] = Vendor::where('vendor_id','=',$lapsedDatesValue->vendor_id)->get(['vendor_id','seo_url','vendor_slug','websiteURL','vendor_company_logo','vendor_fname','vendor_company_details','vendor_company_name']);
	    	$lapsedDatesArray[]=$arr;
	    }

	    $response_array['data']=array('lapsed_dates_details'=>$lapsedDatesArray);
	    return response()->json(['response' => $response_array], $this-> successStatus);
	}
}
