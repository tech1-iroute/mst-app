<?php

namespace App\Http\Controllers\api\v1\ForgotPassword;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Json;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Traits\MacroableTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Validator;
use Input;
use Session;
use Crypt;
use Hash;

class ForgotPasswordController extends Controller
{
	public $successStatus = 200;
    //
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */
    use SendsPasswordResetEmails;
    
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        $this->validate($request, ['user_email' => 'required|email']);
        if ($request->wantsJson()) {
            $user = User::where('user_email', $request->input('user_email'))->first();
            if (!$user) {
                return response()->json(Json::response(null, trans('passwords.user')), 400);
            }
            /*if (is_null($user)) {
                return response()->json(['error'=>'User not found.'], 401); 
            }*/
            $token = $this->broker()->createToken($user);
            return response()->json(Json::response(['token' => $token]));
                //return response()->json([['token' => $token]], 401); 
        }
    }


    public function postForgetpassword(Request $request){

    	$validator = Validator::make($request->all(), [ 
            'user_email'     => 'required|email'
        ]);
        if ($validator->fails()) { 
            return response()->json(['response'=>$validator->errors()], 401);  
        }
        $user_email = $request->get('user_email'); 
        $records = User::where('user_email','=',$user_email)->first();
       	if($records->count() > 0){
            $password = rand(9999,mt_getrandmax());
            $updatepassword = bcrypt($password);
            User::where('pid', $records->pid)->update(array('password' => $updatepassword));
            //$this->_sendpasswordmail($records->user_fname,$records->user_email,$password);
            $response_array['status']='success';
            $response_array['response_message']='Password updated successfully.';
            /*$response_array['response_message']='Password has been sent to your email';*/
            $response_array['data']=array('new_password_detail'=>$password);
            return response()->json(['response' => $response_array], $this-> successStatus); 
       }else{
       		$response_array['status']='fail';
            $response_array['response_message']='You are not registered with us';
            return response()->json(['response'=>$response_array], 401); 
       }
       
       
  	}
  
   	/*private function _sendpasswordmail($user_fname='',$user_email='',$password =''){
        $data = ['name'=>$user_fname, 'to' => $user_email,'password'=>$password];
        \Mail::send('tech@iroute.in', $data, function($message) use($data) {
            $message->to($data['to'])
                    ->subject('Forget Password Notification');
        });

    } */
}
