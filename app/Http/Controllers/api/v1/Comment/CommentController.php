<?php

namespace App\Http\Controllers\api\v1\Comment;

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
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;

class CommentController extends Controller
{
    public $successStatus = 200;

    public function store(Request $request)
    {
    	$request->validate([
            'comment'=>'required',
        ]);
        $input = $request->all();
        $input['cpid'] = $request->input('pid');

        $input['user_id'] = Auth::id();
    
        $comments = PostComments::create($input);
        $response_array['status']='success';
        $response_array['response_message']='Comment done Successfully.';
        $response_array['data']=array('post_comment'=>$comments);
        return response()->json(['response' => $response_array], $this-> successStatus);
    }


    public function destroy($id) {

        $user_id = Auth::id();
        $comment=PostComments::where("cid","=",$id)->where('user_id','=',$user_id)->delete();
      	if (is_null($comment)) {
            return response()->json(['response'=>'Comment not found.'], 401); 
        }
        if ($comment) {
            $response_array['status']='success';
            $response_array['response_message']='Comment deleted Successfully.';
            return response()->json(['response' => $response_array], $this-> successStatus); 
        } else { 
	        $response_array['status']='fail';
	        $response_array['response_message']='Your are not avail to delete this comment.';
	        return response()->json(['response'=>$response_array], 401); 
      } 
       
    }

}
