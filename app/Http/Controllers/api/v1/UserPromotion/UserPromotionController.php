<?php

namespace App\Http\Controllers\api\v1\UserPromotion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\User; 
use App\Post; 
use App\Vendor; 
use App\MainCategory; 
use App\SubCategory; 
use App\CategoryActivity; 
use App\Customer; 
use App\UserVendor; 
use App\UserPromotion; 
use App\VendorEvents;
use App\VendorLead;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Carbon\Carbon;

class UserPromotionController extends Controller
{
    use AuthenticatesUsers;
    public $successStatus = 200;

    public function showPromotion(Request $request){
        
        $user = Auth::user(); 
        $userId = Auth::id();

        $promotionArray = array();
        $arr = array();

        $vendorLeads = VendorLead::where('user','=',$userId)->get(['leadId']);
        $vendorLeadCount = count($vendorLeads);
        if( $vendorLeadCount > 0){
            foreach($vendorLeads as $vendorLead){

                $lead_id=$vendorLead->leadId;
                $sql_promotions = DB::table('tbl_user_promotions')
                ->join('tbl_vendor', 'tbl_user_promotions.vendor_id', '=', 'tbl_vendor.vendor_id')
                ->select('tbl_user_promotions.*', 'tbl_vendor.vendor_company_name', 'tbl_vendor.vendor_company_logo')
                ->where('tbl_user_promotions.user_id','=',$lead_id)
                ->where('tbl_user_promotions.user_type','=','lead') 
                ->get();
                $promotionCount = count($sql_promotions);
                if( $promotionCount > 0){
                    foreach($sql_promotions as $sql_promotion){

                        $promotion_id=$sql_promotion->promotion_id;
                        $user_id=$sql_promotion->user_id;
                        $vendor_id=$sql_promotion->vendor_id;
                        $promotion_accepted=$sql_promotion->promotion_accepted;
                        $created_at = $sql_promotion->created_at;

                        $date = Carbon::now();
                        $dates = $date->format('Y-m-d');

                        $sql_promotion_detail = VendorEvents::where('event_id','=',$promotion_id)->where('end_date','>=',$dates)->get(['event_title','event_description','offer_validity','end_date']);
                        $promotionDetailsCount = count($sql_promotion_detail);
                            if( $promotionDetailsCount > 0){
                                foreach($sql_promotion_detail as $sql_promotion_details){
                                    
                                    $offer_accept_validity = $sql_promotion_details->offer_validity;
                                    $offer_assign_date = date('Y-m-d',strtotime($sql_promotion->created_at));
                                    $offer_accept_end = date('Y-m-d', strtotime($offer_assign_date. " + $offer_accept_validity days"));

                                    $offer_end_date = $sql_promotion_details->end_date;
                                    $diff = abs(strtotime($offer_end_date) - strtotime(date('Y-m-d')));
                                    $expire_days = floor($diff / (86400));                 

                                    $diff = abs(strtotime($offer_accept_end) - strtotime(date('Y-m-d')));
                                    $years = floor($diff / (365*60*60*24));
                                    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                    $arr['userId'] =$user_id;
                                    $arr['vendor_id'] =$vendor_id;
                                    $arr['promotion_id'] =$promotion_id;
                                    $arr['vendor_company_logo'] =$sql_promotion->vendor_company_logo;
                                    $arr['vendor_company_name'] =$sql_promotion->vendor_company_name;
                                    $arr['event_title'] = $sql_promotion_details->event_title;
                                    $arr['event_description'] = $sql_promotion_details->event_description;
                                    if($sql_promotion->promotion_accepted == 'no'){
                                        $arr['button'] ='Accept';
                                        $arr['message'] =$days. ' days left to accept this offer.';
                                    } else { 
                                        $arr['message'] ='This offer will expier after ' .$expire_days. ' days.';
                                    } 

                                    $promotionArray[]=$arr;
                                }

                            }
                    }

                }

                //$promotionArray[]=$arr;
            }
        }
        
        $response_array['status']='success';
        $response_array['data']=array('promotion_details'=>$promotionArray);
        return response()->json(['response' => $response_array], $this-> successStatus);
        
    }


    /*public function acceptPromotion(Request $request, $id, $vendor_id, $promotion_id){*/
    public function acceptPromotion(Request $request){

        //$user_id = $id;
        //$vendor_id = $vendor_id;
        //$promotion_id = $promotion_id;
        $input = $request->all();
        $user_id = $input['user_id'];
        $vendor_id = $input['vendor_id'];
        $promotion_id = $input['promotion_id'];
        $date = Carbon::now();
        $accept_date = $date->format('Y-m-d');

        $UpdateDetails = UserPromotion::where('user_id', $user_id)->where('vendor_id', $vendor_id)->where('promotion_id', $promotion_id)->update([
           'promotion_accepted' => 'yes',
           'accept_date' => $accept_date
        ]);
        if($UpdateDetails){
            $response_array['status']='success';
            $response_array['response_message']='Accept Promotion Successfully.';
            //$response_array['data']=array('status'=>1);
            return response()->json(['response' => $response_array], $this-> successStatus);
        } else {
            $response_array['status']='fail';
            $response_array['response_message']='There is issues related accept this request.';
            //$response_array['data']=array('status'=>0);
            return response()->json(['response' => $response_array], $this-> successStatus);

        }
        
      }
}
