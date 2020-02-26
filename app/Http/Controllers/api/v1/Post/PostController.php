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
use App\UserActivity; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Carbon\Carbon;

class PostController extends Controller{

use AuthenticatesUsers;
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
      $userVendor = UserVendor::where('user_id','=',$userId)->get(['vendor_id'])->toArray();
      foreach($userVendor as $value){
         $vendorIds[] = $value['vendor_id'];     
      }

      if ($page === 1) {
          $perPage = 5;
      } else {
          $perPage = 5 * $page;
      }

      $postDetails = $post->whereIn('store_id',$vendorIds)->take($perPage)->get(); 
      $postCount = count($postDetails);
      $arr = array();
      //$user_act = array();
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

        $results=DB::table('tbl_user_activity')->where("product_id","=",$postValue->pid)->where('user_id','=',$userId)->get()->pluck('reason_id')->toArray();

        $user_activities = CategoryActivity::where('interest_id','=',$postValue->product_interest_new)->where('category_id','=',$postValue->user_interest)->orWhere('category_id','=',0)->where('status','=',1)->get(['reason_id','reason_name','icon','interest_id',DB::raw($postValue->pid.' as pid')])->toArray();
        $final = array();
        foreach($user_activities as $user_activity){
          if (in_array($user_activity['reason_id'], $results)) {
            $final[] = ['reason_id'=>$user_activity['reason_id'],'reason_name'=>$user_activity['reason_name'],'icon'=>$user_activity['icon'],'interest_id'=>$user_activity['interest_id'],'pid'=>$user_activity['pid'],'selected'=>'yes'];
           } else {
            $final[] = ['reason_id'=>$user_activity['reason_id'],'reason_name'=>$user_activity['reason_name'],'icon'=>$user_activity['icon'],'interest_id'=>$user_activity['interest_id'],'pid'=>$user_activity['pid'],'selected'=>'no'];
          }
          $arr['Activities']=$final;
        }


        $arr['user_activity_messages'] = $this->userActivityMessage($postValue->product_interest_new,$postValue->user_interest,$postValue->pid,$userId);

       /* $allPostComments = PostComments::where('cpid','=',$postValue->pid)->get(['cid','comment','user_id']);
        $allPostCommentsCount = count($allPostComments);

        if($allPostCommentsCount > 1){
          $arr['postMoreComments'] = $this->show_more($postValue->pid);
        }*/

        //$arr['postComments'] = PostComments::where('cpid','=',$postValue->pid)->take(1)->get(['cid','comment','user_id']);
        
        /*$arr['postComments'] = DB::table('comments')
                ->join('tbl_user', 'comments.user_id', '=', 'tbl_user.pid')
                ->select('comments.*', 'tbl_user.user_image')
                ->where('comments.cpid','=',$postValue->pid)
                ->take(1)->get();*/
        $arr['postComments'] = DB::table('comments')
                ->join('tbl_user', 'comments.user_id', '=', 'tbl_user.pid')
                ->select('comments.*', 'tbl_user.user_image')
                ->where('comments.cpid','=',$postValue->pid)
                ->get();
        $postArray[]=$arr;

      }
      $response_array['data']=array('feed_heading'=>"Socialtab",'feed_description'=>"Shows you brand posts related to your vendors",'post_details'=>$postArray);
      return response()->json(['response' => $response_array], $this-> successStatus);

    }

    public function userActivityMessage($mainCategory, $subCategory, $pid, $userId){

      $arrNew = array();
      $mainCategory = $mainCategory;
      $subCategory = $subCategory;
      $results =UserActivity::where("product_id","=",$pid)->where('user_id','=',$userId)->get();

      $user_activity_messages = CategoryActivity::where('interest_id','=',$mainCategory)->where('status','>',0)->where('category_id','=',$subCategory)->orWhere('category_id','=',0)->orderBy('reason_id', 'ASC')->get()->toArray();

      $activityMessagesCount = count($user_activity_messages);

      if( $activityMessagesCount > 0){
      $activity_message = array();

      foreach($user_activity_messages as $user_activity_message){
        $reason_id=$user_activity_message['reason_id'];
        $pid=$pid;
        $sql_user_act = DB::table('tbl_user_activity')
                ->join('tbl_user', 'tbl_user_activity.user_id', '=', 'tbl_user.pid')
                ->select('tbl_user_activity.*', 'tbl_user.*')
                ->where('tbl_user_activity.product_id','=',$pid)
                ->where('tbl_user_activity.reason_id','=',$reason_id) 
                ->get();
        $messagesCount = count($sql_user_act);
        if($messagesCount > 0){
          if($results->contains('reason_id', $reason_id)){
            if($messagesCount==1){
                  $activity_message[] = ['message'=>"You ".$user_activity_message['you']."",'icon'=>$user_activity_message['icon']];
                  
            }elseif($messagesCount==2){
                  $oth=$messagesCount-1;
                  $activity_message[] = ['message'=>"You and $oth other ".$user_activity_message['you_1'],'icon'=>$user_activity_message['icon']];
                  
            }else{
                  $oth=$messagesCount-1;
                  $activity_message[] = ['message'=>"You and $oth other's ".$user_activity_message['you_o'],'icon'=>$user_activity_message['icon']];
                  
            }
          } else {
              if($messagesCount==1){
                if($user_activity_message['single']=="bookmarked this"){
                    $activity_message[] = ['message'=>"1 person ".$user_activity_message['single']."",'icon'=>$user_activity_message['icon']];
                     
                } else {
                    $activity_message[] = ['message'=>"1 person ".$user_activity_message['single']."",'icon'=>$user_activity_message['icon']];
                    
                }   
              } else {
                $activity_message[] =  ['message'=>$messagesCount." people  ".$user_activity_message['single_o'],'icon'=>$user_activity_message['icon']];
                
              }
            } 
          }
          $arrNew=$activity_message;
        }

        return $arrNew;
      }
    }

    public function show_more($id) 
    {
        $post_comment =PostComments::where('cpid','=',$id)->skip(1)->take(1000)->get(['cid','comment','user_id']);
        return $post_comment;
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
          $result1 = $this->getActivityCount($arr['pid']);
          $result2 = $this->getCommentCount($arr['pid']);
          $arr['tab_score'] = $result1 + $result2;
          $pageURL = 'https://mysocialtab.com/post/'.$post_name.'/'.$arr['pid'].'';
          $arr['veiwCount'] = PostClickCount::where('page_url','=',$pageURL)->get(['page_count']);
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
          $date = Carbon::parse($relatedPostValue->date); // now date is a carbon instance
          $relatedArr['date'] = $date->diffForHumans(Carbon::now());
          $relatedArr['prod_url'] = $relatedPostValue->prod_url;
          $relatedArr['product_interest_new'] = $relatedPostValue->product_interest_new;
          $relatedArr['user_interest'] = $relatedPostValue->user_interest;
          $relatedArr['store_id'] = $relatedPostValue->store_id;
          $relatedArr['uploaded_by_id'] = $relatedPostValue->uploaded_by_id;
          $relatedArr['subCategory'] = SubCategory::find($relatedPostValue->user_interest);
          $relatedPostArray[]=$relatedArr;

        }
          $response_array['data']=array('single_post_details'=>$postArray, 'related_post'=>$relatedPostArray);
          return response()->json(['response' => $response_array], $this-> successStatus);

    }


    public function feed_single_page_click_increment(Request $request){
      $post = new Post();
      $validator = Validator::make($request->all(), [ 
        'post_id' => 'required',
      ]);
      if ($validator->fails()) { 
        return response()->json(['response'=>$validator->errors()], 401);            
      }
      $input = $request->all(); 
      $post_id = $input['post_id'];
      $postClicksUpdate = $post->where('pid',$post_id)->update(['clicks' => DB::raw('clicks + 1')]);; 
      $postDetails = $post->where('pid',$post_id)->get(['clicks']);
      $response_array['status']='success';
      $response_array['response_message']='Click successfully.';
      $response_array['data']=array('postDetails'=>$postDetails);
      return response()->json(['response' => $response_array], $this-> successStatus);

    }

    public function getActivityCount($post_id){
        $UserActivity = UserActivity::where("product_id","=",$post_id)->get();
        $UserActivityCount = count($UserActivity);
        return $UserActivityCount;
    }

    public function getCommentCount($post_id){
        $UserComments = PostComments::where('cpid','=',$post_id)->get(['cid','comment','user_id']);
        $UserCommentsCount = count($UserComments);
        return $UserCommentsCount;
    }
}
