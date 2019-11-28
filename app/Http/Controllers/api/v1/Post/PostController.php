<?php

namespace App\Http\Controllers\api\v1\Post;

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
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;

class PostController extends Controller{

public $successStatus = 200;
    
    public function profile(){

      $user = Auth::user(); 
      $userId = Auth::id();
      $postArray = array();
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
            $arr['store_id'] = $postValue->store_id;
            $arr['vendorDetail'] = Vendor::find($postValue->store_id);
            $arr['mainCategory'] = MainCategory::find($arr['product_interest_new']);
            $arr['subCategory'] = SubCategory::find($arr['user_interest']);
            $arr['Activities'] = CategoryActivity::where('interest_id','=',$arr['product_interest_new'])->where('category_id','=',$arr['user_interest'])->orWhere('category_id','=',0)->get(['reason_id','reason_name']);
            $postArray[]=$arr;

          }
      
        }
        
        $response_array['data']=array('post_details'=>$postArray);
        return response()->json(['response' => $response_array], $this-> successStatus);

    }

     public function feed(){

      $post = new Post();
      $user = Auth::user(); 
      $userId = Auth::id();
      $postArray = array();
      $userVendor = UserVendor::where('user_id','=',$userId)->distinct()->get(['customer_id','vendor_id']);

      foreach($userVendor as $value){

          $vendorId = $value->vendor_id;
          $postDetails = $post->where('store_id', $vendorId)->get(); 
          $arr = array();

          foreach($postDetails as $postValue){

            $arr['pid'] = $postValue->pid;
            $arr['prod_name'] = $postValue->prod_name;
            $arr['prod_all_img'] = $postValue->prod_all_img;
            $arr['prod_url'] = $postValue->prod_url;
            $arr['product_interest_new'] = $postValue->product_interest_new;
            $arr['user_interest'] = $postValue->user_interest;
            $arr['store_id'] = $postValue->store_id;
            $arr['vendorDetail'] = Vendor::find($postValue->store_id);
            $arr['mainCategory'] = MainCategory::find($postValue->product_interest_new);
            $arr['subCategory'] = SubCategory::find($postValue->user_interest);
            $arr['Activities'] = CategoryActivity::where('interest_id','=',$postValue->product_interest_new)->where('category_id','=',$postValue->user_interest)->orWhere('category_id','=',0)->get(['reason_id','reason_name']);
            $postArray[]=$arr;

          }
      
      }

      $response_array['data']=array('post_details'=>$postArray);
      return response()->json(['response' => $response_array], $this-> successStatus);

    }
}
