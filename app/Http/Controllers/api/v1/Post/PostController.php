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
use App\PostClickCount; 
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
      $post = new Post();
      $user = Auth::user(); 
      $userId = Auth::id();
      $postArray = array();
      //$vendorIds = array();
      $userVendor = UserVendor::where('user_id','=',$userId)->get(['vendor_id'])->toArray();
      //print_r($userVendor); die;
      foreach($userVendor as $value){
         $vendorIds[] = $value['vendor_id'];     
      }
      //$vendorId = implode(',', $vendorIds);
      //$perPage = 5;
      if ($page === 1) {
          //$skip = $page-1;
          $perPage = 5;
      } else {
          //$skip = $perPage * ($page-1);
          $perPage = 5 * $page;
      }

      //$postDetails = $post->whereIn('store_id',$vendorIds)->skip($skip)->take($perPage)->get(); 
        $postDetails = $post->whereIn('store_id',$vendorIds)->take($perPage)->get(); 
        $postCount = count($postDetails);
        $arr = array();
        foreach($postDetails as $postValue){

          $arr['pid'] = $postValue->pid;
          $arr['prod_name'] = $postValue->prod_name;
          $arr['prod_desc'] = $postValue->prod_desc;
          $arr['prod_all_img'] = $postValue->prod_all_img;
          if ($postValue->prod_status == 'B') { 
              $arr['prod_status'] ="Sponsored";
          } else if ($postValue->prod_status == 'S') {
              $arr['prod_status'] ="System Generated";
          } else {
              $arr['prod_status'] ="User Generated";
          }
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
            $response_array['data']=array('feed_heading'=>"Socialtab",'feed_description'=>"Shows you brand posts related to your vendors",'post_details'=>$postArray);
            return response()->json(['response' => $response_array], $this-> successStatus);

    }



    public function feed_single_detail_page(Request $request, $post_id){
      $post = new Post();
      $user = Auth::user(); 
      $userId = Auth::id();
      $postArray = array();
      $relatedPostArray = array();
  
        $postDetails = $post->where('pid',$post_id)->get(); 
        $postCount = count($postDetails);
        $arr = array();
        foreach($postDetails as $postValue){

          $arr['pid'] = $postValue->pid;
          $arr['prod_name'] = $postValue->prod_name;
          $arr['prod_desc'] = $postValue->prod_desc;
          $arr['prod_all_img'] = $postValue->prod_all_img;
          if ($postValue->prod_status == 'B') { 
              $arr['prod_status'] ="Sponsored";
          } else if ($postValue->prod_status == 'S') {
              $arr['prod_status'] ="System Generated";
          } else {
              $arr['prod_status'] ="User Generated";
          }
          $post_name= str_replace(" ","-",trim(strtolower(preg_replace('/[^A-Za-z0-9\-\(\) ]/','',$postValue->prod_name))));
          $pageURL = 'https://mysocialtab.com/post/'.$post_name.'/'.$arr['pid'].'';
          echo $arr['veiwCount'] = PostClickCount::where('page_url','=',$pageURL)->get(['page_count']);
          $arr['clicks'] = $postValue->clicks;
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
          $arr['postCommentsCount'] = count($arr['postComments']);
          $postArray[]=$arr;

        }

        $relatedPostDetails = $post->where('pid','!=',$post_id)->where('store_id',$arr['store_id'])->get(); 
        $relatedPostCount = count($relatedPostDetails);
        $relatedArr = array();
        foreach($relatedPostDetails as $relatedPostValue){

          $relatedArr['pid'] = $relatedPostValue->pid;
          $relatedArr['prod_name'] = $relatedPostValue->prod_name;
          $relatedArr['prod_desc'] = $relatedPostValue->prod_desc;
          $relatedArr['prod_all_img'] = $relatedPostValue->prod_all_img;
          //$relatedArr['clicks'] = $relatedPostValue->clicks;
          $date = Carbon::parse($relatedPostValue->date); // now date is a carbon instance
          $relatedArr['date'] = $date->diffForHumans(Carbon::now());
          $relatedArr['prod_url'] = $relatedPostValue->prod_url;
          $relatedArr['product_interest_new'] = $relatedPostValue->product_interest_new;
          $relatedArr['user_interest'] = $relatedPostValue->user_interest;
          $relatedArr['store_id'] = $relatedPostValue->store_id;
          $relatedArr['uploaded_by_id'] = $relatedPostValue->uploaded_by_id;
          //$relatedArr['vendorDetail'] = Vendor::find($relatedPostValue->store_id);
          //$relatedArr['mainCategory'] = MainCategory::find($relatedPostValue->product_interest_new);
          $relatedArr['subCategory'] = SubCategory::find($relatedPostValue->user_interest);
         
          $relatedPostArray[]=$relatedArr;

        }
          $response_array['data']=array('single_post_details'=>$postArray, 'related_post'=>$relatedPostArray);
          return response()->json(['response' => $response_array], $this-> successStatus);

    }
}
