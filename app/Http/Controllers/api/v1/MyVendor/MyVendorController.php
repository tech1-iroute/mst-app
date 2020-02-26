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
use App\UserVendor;
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
       
        $sql_my_vendor = DB::table('tbl_user_vendor')
                    ->join('tbl_vendor', 'tbl_user_vendor.vendor_id', '=', 'tbl_vendor.vendor_id')
                    ->join('tbl_customer', 'tbl_user_vendor.customer_id', '=', 'tbl_customer.customer_id')
                    ->select('tbl_user_vendor.allow_feed', 'tbl_user_vendor.request_accepted', 'tbl_vendor.*', 'tbl_customer.customer_id', 'tbl_customer.customer_email', 'tbl_customer.mst_id', 'tbl_customer.mst_genie')
                    ->where('tbl_user_vendor.user_id','=',$user_id)
                    ->where('tbl_user_vendor.request_accepted','=','yes')
                    ->get();
        $rowCount = count($sql_my_vendor);
        if($rowCount > 0) {
            $arr = array();
            foreach($sql_my_vendor as $list){

                $arr['customer_id'] = $list->customer_id;
                $arr['user_id'] = $user_id;
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
                    ->select('tbl_user_vendor.allow_feed', 'tbl_user_vendor.request_accepted', 'tbl_vendor.*', 
                        'tbl_customer.customer_id', 'tbl_customer.customer_email', 'tbl_customer.mst_id', 'tbl_customer.mst_genie')
                    ->where('tbl_user_vendor.user_id','=',$user_id)
                    ->where('tbl_user_vendor.request_accepted','=',null)
                    ->get();
        $rowCount = count($sql_my_vendor);
        if($rowCount > 0) {
            $arr = array();
            foreach($sql_my_vendor as $list){

                $arr['customer_id'] = $list->customer_id;
                $arr['user_id'] = $user_id;
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
                
                
                if(is_null($arr['request_accepted'])){  
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



        public function acceptMyVendor(Request $request){

        $input = $request->all();
        $user_id = $input['user_id'];
        $vendor_id = $input['vendor_id'];
        $customer_id = $input['customer_id'];
        $request_accepted = $input['request_accepted'];
        $allow_feed = $request_accepted;

        $UpdateDetails = UserVendor::where('customer_id', $customer_id)->where('user_id', $user_id)->where('vendor_id', $vendor_id)->update([
           'request_accepted' => $request_accepted,
           'allow_feed' => $allow_feed
        ]);
        if($UpdateDetails){
            $response_array['status']='success';
            $response_array['response_message']='Record Updated.';
            return response()->json(['response' => $response_array], $this-> successStatus);
        } else {
            $response_array['status']='fail';
            $response_array['response_message']='There is issues related accept this request.';
            return response()->json(['response' => $response_array], $this-> successStatus);

        }
        
      }


      public function rejectMyVendor(Request $request){

        $input = $request->all();
        $user_id = $input['user_id'];
        $vendor_id = $input['vendor_id'];
        $customer_id = $input['customer_id'];
        $request_accepted = $input['request_accepted'];
        $allow_feed = $request_accepted;

        $UpdateDetails = UserVendor::where('customer_id', $customer_id)->where('user_id', $user_id)->where('vendor_id', $vendor_id)->update([
           'request_accepted' => $request_accepted,
           'allow_feed' => $allow_feed
        ]);
        if($UpdateDetails){
            $response_array['status']='success';
            $response_array['response_message']='Record Updated.';
            return response()->json(['response' => $response_array], $this-> successStatus);
        } else {
            $response_array['status']='fail';
            $response_array['response_message']='There is issues related accept this request.';
            return response()->json(['response' => $response_array], $this-> successStatus);

        }
        
      }
     
}
