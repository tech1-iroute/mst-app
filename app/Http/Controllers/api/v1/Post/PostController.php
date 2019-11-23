<?php

namespace App\Http\Controllers\api\v1\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\User; 
use App\Post; 
use App\Vendor; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;

class PostController extends Controller
{
    //
    public $successStatus = 200;
    public function feed(){
      $user = Auth::user(); 
      $userId = Auth::id();
      //$userVendor = $user->vendor ->All();
      $userVendor = $user->vendor ->where('mst_id', $userId)->get();
      foreach($userVendor as $value){
          $vendorId = $value->vendor_id;
          $postDetails = $user->posts ->whereIn('store_id', $vendorId)->All(); 
          $arr = array();
          foreach($postDetails as $postValue){
            $arr['pid'] = $postValue->pid;
            $arr['prod_name'] = $postValue->prod_name;
            $arr['prod_all_img'] = $postValue->prod_all_img;
            $arr['prod_url'] = $postValue->prod_url;
            $arr['product_interest_new'] = $postValue->product_interest_new;
            $arr['user_interest'] = $postValue->user_interest;
            $postArray[]=$arr;
            //$mainCategory = DB::select("select * from tbl_user_interest_new where 'interest_id'=".$arr['product_interest_new']);
            //$subCategory = DB::select("select * from tbl_user_interest where 'parentId'='".$mainCategory['interest_id']."' && 'interest_id'=".$arr['user_interest']);
          }
      
        }
        /*$response_array['data']=array('post_details'=>$postArray,'vendor_details'=>$userVendor,'mainCategory'=>$mainCategory,'subCategory'=>$subCategory);*/
        $response_array['data']=array('post_details'=>$postArray,'vendor_details'=>$userVendor);
        return response()->json(['response' => $response_array], $this-> successStatus);
    }
}
