<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/login', 'api\v1\User\UserController@login');
//Route::post('user/login', function(){echo 'Hello';die;});
Route::post('user/register', 'api\v1\User\UserController@register');

Route::get('login/facebook', 'api\v1\User\UserController@redirectToProvider');
// Route::get('login/facebook', function(){echo 'Hello';die;});
Route::get('login/facebook/callback', 'api\v1\User\UserController@handleProviderCallback');

Route::group(['middleware' => 'auth:api'], function(){
	/* User user_details , user_details_update, logout */
	Route::get('user/details', 'api\v1\User\UserController@details');
	Route::get('user/user_details/{id}', 'api\v1\User\UserController@user_details');
	Route::put('user/update/{id}', 'api\v1\User\UserController@update');
	Route::get('user/logout', 'api\v1\User\UserController@logout');

	Route::get('user/getPosts', 'api\v1\User\UserController@getPosts');
	Route::get('user/getVendor', 'api\v1\User\UserController@getVendor');
	Route::get('post/feed', 'api\v1\Post\PostController@feed');
	Route::get('post/profile', 'api\v1\Post\PostController@profile');
	Route::post('forgotPassword/email', 'api\v1\ForgotPassword\ForgotPasswordController@userForgetpassword');
	Route::post('post_comment/store', 'api\v1\Comment\CommentController@store');
	Route::delete('post_comment/delete/{id}', 'api\v1\Comment\CommentController@destroy');


});




