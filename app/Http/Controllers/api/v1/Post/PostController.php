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
use App\PostComments; 
use App\Bookmark; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;
use Carbon\Carbon;

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
            $arr['prod_desc'] = $postValue->prod_desc;
            $arr['prod_all_img'] = $postValue->prod_all_img;
            $date = Carbon::parse($postValue->date); // now date is a carbon instance
            $arr['date'] = $date->diffForHumans(Carbon::now());
            $arr['prod_url'] = $postValue->prod_url;
            $arr['product_interest_new'] = $postValue->product_interest_new;
            $arr['user_interest'] = $postValue->user_interest;
            $arr['store_id'] = $postValue->store_id;
            $arr['uploaded_by_id'] = $postValue->uploaded_by_id;
            $arr['vendorDetail'] = Vendor::find($postValue->store_id);
            $arr['mainCategory'] = MainCategory::find($arr['product_interest_new']);
            $arr['subCategory'] = SubCategory::find($arr['user_interest']);
            
            $arr['Activities'] = CategoryActivity::where('interest_id','=',$arr['product_interest_new'])->where('category_id','=',$arr['user_interest'])->orWhere('category_id','=',0)->where('status','=',1)->get(['reason_id','reason_name','icon','interest_id']);

            $arr['postComments'] = PostComments::where('cpid','=',$arr['pid'])->get(['cid','comment','user_id']);
            $postArray[]=$arr;

          }
      
        }
        
        $response_array['data']=array('post_details'=>$postArray);
        return response()->json(['response' => $response_array], $this-> successStatus);

    }

     public function feed($page=1){
      //echo $page; die;

      $post = new Post();
      $user = Auth::user(); 
      $userId = Auth::id();
      $postArray = array();
      $userVendor = UserVendor::where('user_id','=',$userId)->distinct()->get(['customer_id','vendor_id']);
      //$input = $request->all(); 
      $perPage = 5;
      if ($page === 1) {
          $skip = $page-1;
      } else {
          $skip = $perPage * ($page-1);
      }
      foreach($userVendor as $value){

          $vendorId = $value->vendor_id;
          $postDetails = $post->where('store_id', $vendorId)->skip($skip)->take($perPage)->get(); 
          $arr = array();

          foreach($postDetails as $postValue){

            $arr['pid'] = $postValue->pid;
            $arr['prod_name'] = $postValue->prod_name;
            $arr['prod_desc'] = $postValue->prod_desc;
            $arr['prod_all_img'] = $postValue->prod_all_img;
            //$arr['date'] = $postValue->date;
            $date = Carbon::parse($postValue->date); // now date is a carbon instance
            $arr['date'] = $date->diffForHumans(Carbon::now());
            $arr['prod_url'] = $postValue->prod_url;
            $arr['product_interest_new'] = $postValue->product_interest_new;
            $arr['user_interest'] = $postValue->user_interest;
            $arr['store_id'] = $postValue->store_id;
            $arr['uploaded_by_id'] = $postValue->uploaded_by_id;
            $arr['vendorDetail'] = Vendor::find($postValue->store_id);
            $arr['mainCategory'] = MainCategory::find($postValue->product_interest_new);
            $arr['subCategory'] = SubCategory::find($postValue->user_interest);
            
            $arr['Activities'] = CategoryActivity::where('interest_id','=',$postValue->product_interest_new)->where('category_id','=',$postValue->user_interest)->orWhere('category_id','=',0)->where('status','=',1)->get(['reason_id','reason_name','icon','interest_id',DB::raw($postValue->pid.' as pid')]);
            
            $arr['postComments'] = PostComments::where('cpid','=',$postValue->pid)->get(['cid','comment','user_id']);

            $postArray[]=$arr;

          }
      
      }

      $response_array['data']=array('feed_heading'=>"Socialtab",'feed_description'=>"Shows you brand posts related to your vendors",'post_details'=>$postArray);
      return response()->json(['response' => $response_array], $this-> successStatus);

    }
}
