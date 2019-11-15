<?php
namespace App\Http\Controllers\api\v1\User;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

class UserController extends Controller 
{
public $successStatus = 200;
use AuthenticatesUsers;

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 
      //echo 'hi'; die;
            $validator = Validator::make($request->all(), [ 
                'user_mobile' => 'required|min:11|numeric',
                'password' => ['required', 'string', 'min:6'],
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);  
            }
            if(Auth::attempt(['user_mobile' => request('user_mobile'), 'password' => request('password')])){ 
                $user = Auth::user(); 
                $token =  $user->createToken('mst-app')-> accessToken; 

                $response_array['status']='success';
                $response_array['response_message']='User successfully logged in';
                $response_array['data']=array('token'=>$token,'user_details'=>$user);
                return response()->json(['response' => $response_array], $this-> successStatus); 
            } 
            else{ 
                return response()->json(['error'=>'Unauthorised'], 401); 
            } 
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
      //echo 'hi'; die;
        $validator = Validator::make($request->all(), [ 
            'user_fname' => ['required', 'string', 'max:255'], 
            'user_lname' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'], 
            'user_mobile' => 'required|min:11|numeric|unique:tbl_user',
            'dob' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['user_code'] = $this->generateRandomString(6);// it should be dynamic and unique 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('mst-app')-> accessToken; 
        //$success['user_fname'] =  $user->user_fname;
        //$user = Auth::user(); 
        $success[] =  $user;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [ 
            'user_fname' => ['required', 'string', 'max:255'], 
            'user_lname' => ['required', 'string', 'max:255'],
            'dob' => 'required',
        ]);
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        $user = User::find($id);
        //print_r($user); die;
        $input = $request->all();
        $user->user_fname = $input['user_fname'];
        $user->user_lname = $input['user_lname'];
        $user->dob = $input['dob'];
        $user->save();
        $success[] =  $user;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    public function user_details($id) 
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['error'=>'User not found.'], 401); 
        }
        return response()->json(['success' => $user], $this-> successStatus);
    }

    public function logout(Request $request)
    {
        $success['success'] =  true;
        $success['message'] =  'Successfully logged out';
        $request->User()->token()->revoke();
        return response()->json(['result'=>$success], $this-> successStatus); 
    }


    /**
     * Redirect the user to the facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return  Socialite::driver('facebook')->redirect();
    }


    /**
     * Obtain the user information from facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();
        $user = User::where('facebook_id',$facebookUser->facebook_id())->first();
        if(!$user){
            //add users to database
            $user = User::create([
              'user_fname'     => $facebookUser->name(),
              'user_email'    => $facebookUser->email(),
              'facebook_id' => $facebookUser->id(),
              //'provider_id' => $user->id,
            ]);
        }
        $success[] =  $user;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public  function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'GINI'.$randomString;
    }

}