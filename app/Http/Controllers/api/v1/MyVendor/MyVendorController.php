<?php

namespace App\Http\Controllers\api\v1\MyVendor;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Post; 
use App\Vendor; 
use App\MainCategory; 
use App\SubCategory; 
use App\CategoryActivity; 
use App\GiftPreference; 
use App\Customer;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class MyVendorController extends Controller
{
	public $successStatus = 200;

        public function getMyVendor(){
        $user_id = Auth::id();
        $vendorArray = array();
        /*$sql_user_act = DB::table('tbl_user_activity')
                    ->join('tbl_user', 'tbl_user_activity.user_id', '=', 'tbl_user.pid')
                    ->select('tbl_user_activity.*', 'tbl_user.*')
                    ->where('tbl_user_activity.product_id','=',$pid)
                    ->where('tbl_user_activity.reason_id','=',$reason_id) 
                    ->get();*/
        //$sql="SELECT vdr.vendor_id,vdr.seo_url, vdr.vendor_fname, vdr.vendor_company_logo, vdr.brandBanner, vdr.vendor_company_name,cust.customer_id, cust.customer_email, cust.mst_id, cust.mst_genie, cust.cron_data, cust.cron_request_status, cust.calendar_permission,usr_vdr.allow_feed,usr_vdr.request_accepted FROM tbl_customer cust, tbl_user_vendor usr_vdr,tbl_vendor vdr WHERE vdr.vendor_id = usr_vdr.vendor_id AND  cust.customer_id = usr_vdr.customer_id AND usr_vdr.user_id = $userId ";

        $sql_my_vendor = DB::table('tbl_user_vendor')
                    ->join('tbl_vendor', 'tbl_user_vendor.vendor_id', '=', 'tbl_vendor.vendor_id')
                    ->join('tbl_customer', 'tbl_user_vendor.customer_id', '=', 'tbl_customer.customer_id')
                    ->select('tbl_user_vendor.*', 'tbl_vendor.*', 'tbl_customer.*')
                    ->where('tbl_user_vendor.user_id','=',$user_id)
                    ->where('tbl_user_vendor.request_accepted','=','yes')
                    ->get();
        $rowCount = count($sql_my_vendor);
        if($rowCount > 0) {
            $arr = array();
            foreach($sql_my_vendor as $list){

                $arr['vendor_id'] =   $list->vendor_id;
                $arr['vendor_personaName'] =   $list->vendor_personaName;
                $arr['request_accepted'] =   $list->request_accepted;
                if($list->vendor_company_logo !=''){
                    $arr['companyLogo'] =   $list->vendor_company_logo;
                }else{
                    $arr['companyLogo'] = '<i class="fa fa-building"></i>';
                }
                if($list->brandBanner !=''){
                    $arr['brandBanner'] =   $list->brandBanner;
                }else{
                    $arr['brandBanner'] = '<i class="fa fa-building"></i>';
                }

                $arr['companyName'] =   $list->vendor_company_name;
                
                if($list->request_accepted == 'yes'){
                    $arr['companyView'] =   'View';
                }
                /*if(is_null($arr['request_accepted'])){ 
                //if($list->request_accepted == null){ 
                    $arr['companyYes'] =   '<i class="fa fa-check" aria-hidden="true"></i>';
                    $arr['companyNo'] =   '<i class="fa fa-close" aria-hidden="true"></i>';
                }elseif($list->request_accepted == 'yes'){
                    $arr['companyView'] =   'View';
                }*/
                $vendorArray[]=$arr;

            }

        }
        $response_array['status']='success';
        $response_array['response_message']='Successfully';
        $response_array['data']=array('vendor_details'=>$vendorArray);
        return response()->json(['response' => $response_array], $this-> successStatus);
        }



        public function getPendingVendor(){
        $user_id = Auth::id();
        $vendorArray = array();
       
        $sql_my_vendor = DB::table('tbl_user_vendor')
                    ->join('tbl_vendor', 'tbl_user_vendor.vendor_id', '=', 'tbl_vendor.vendor_id')
                    ->join('tbl_customer', 'tbl_user_vendor.customer_id', '=', 'tbl_customer.customer_id')
                    ->select('tbl_user_vendor.*', 'tbl_vendor.*', 'tbl_customer.*')
                    ->where('tbl_user_vendor.user_id','=',$user_id)
                    ->where('tbl_user_vendor.request_accepted','=',null)
                    ->get();
        $rowCount = count($sql_my_vendor);
        if($rowCount > 0) {
            $arr = array();
            foreach($sql_my_vendor as $list){

                $arr['vendor_id'] =   $list->vendor_id;
                $arr['vendor_personaName'] =   $list->vendor_personaName;
                $arr['request_accepted'] =   $list->request_accepted;
                if($list->vendor_company_logo !=''){
                    $arr['companyLogo'] =   $list->vendor_company_logo;
                }else{
                    $arr['companyLogo'] = '<i class="fa fa-building"></i>';
                }
                if($list->brandBanner !=''){
                    $arr['brandBanner'] =   $list->brandBanner;
                }else{
                    $arr['brandBanner'] = '<i class="fa fa-building"></i>';
                }

                $arr['companyName'] =   $list->vendor_company_name;
                
                /*if($list->request_accepted == 'yes'){
                    $arr['companyView'] =   'View';
                }*/
                if(is_null($arr['request_accepted'])){ 
                //if($list->request_accepted == null){ 
                    $arr['companyYes'] =   '<i class="fa fa-check" aria-hidden="true"></i>';
                    $arr['companyNo'] =   '<i class="fa fa-close" aria-hidden="true"></i>';
                }elseif($list->request_accepted == 'yes'){
                    $arr['companyView'] =   'View';
                }
                $vendorArray[]=$arr;

            }

        }
        $response_array['status']='success';
        $response_array['response_message']='Successfully';
        $response_array['data']=array('vendor_details'=>$vendorArray);
        return response()->json(['response' => $response_array], $this-> successStatus);
        }
     
}
