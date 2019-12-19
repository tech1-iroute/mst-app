<?php

namespace App\Http\Controllers\api\v1\Bookmark;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Vendor; 
use App\Bookmark; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;

class BookmarkController extends Controller
{
	public $successStatus = 200;
    public function show(){

    	$arr = array();
    	$bookmarkArray = array();

	    $user_id    = Auth::id();
	    $bookmarkDetails   = Bookmark::where('user_id', '=', $user_id)->where('brandID', '!=' , 0)->get();
	    foreach($bookmarkDetails as $bookmarkValue){
	    	$arr['bookmark_dt'] = $bookmarkValue->bookmark_dt;
	    	$arr['vendorDetail'] = Vendor::where('vendor_id','=',$bookmarkValue->brandID)->get(['vendor_id','seo_url','vendor_slug','websiteURL','vendor_company_logo','vendor_fname','vendor_company_details']);
	    	$bookmarkArray[]=$arr;
	    }

	    $response_array['data']=array('bookmark_details'=>$bookmarkArray);
	    return response()->json(['response' => $response_array], $this-> successStatus);
	}
}
