<?php

namespace App\Http\Controllers\api\v1\UserActivity;

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
use App\UserActivity; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;
use Carbon\Carbon;

class UserActivityController extends Controller
{
    public $successStatus = 200;

    public function store(Request $request)
    {

        $input = $request->all();
        $input['product_id'] = $request->input('pid');
        $input['user_id'] = Auth::id();
        $mytime = Carbon::now();
		$input['activity_date'] = $mytime->toDateTimeString();

        $UserActivity = UserActivity::where("product_id","=",$input['product_id'])->where('user_id','=',$input['user_id'])->first();

        if ($UserActivity) {
            $DeleteUserActivity=UserActivity::where("product_id","=",$input['product_id'])->where('user_id','=',$input['user_id'])->delete();
        }
        $activity = UserActivity::create($input);
        $response_array['status']='success';
        $response_array['response_message']='Activity done Successfully.';
        $response_array['data']=array('activity_status'=>'checked');
        return response()->json(['response' => $response_array], $this-> successStatus);
    }
}
