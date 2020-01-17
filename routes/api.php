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


Route::post('forgotPassword/email', 'api\v1\ForgotPassword\ForgotPasswordController@userForgetpassword');
Route::post('password/email', 'api\v1\ForgotPassword\ForgotPasswordController@getResetToken');
Route::post('password/reset', 'api\v1\ResetPassword\ResetPasswordController@reset');

Route::group(['middleware' => 'auth:api'], function(){

	Route::get('user/details', 'api\v1\User\UserController@details');
	Route::get('user/user_details/{id}', 'api\v1\User\UserController@user_details');
	Route::put('user/update/{id}', 'api\v1\User\UserController@update');
	Route::get('user/logout', 'api\v1\User\UserController@logout');

	Route::get('user/getPosts', 'api\v1\User\UserController@getPosts');
	Route::get('user/getVendor', 'api\v1\User\UserController@getVendor');

	Route::get('post/feed/{page?}', 'api\v1\Post\PostController@feed');
	Route::get('post/feed_single_detail_page/{id}', 'api\v1\Post\PostController@feed_single_detail_page');
	Route::get('post/profile', 'api\v1\Post\PostController@profile');
	Route::post('post/feed_single_page_click_increment', 'api\v1\Post\PostController@feed_single_page_click_increment');

	Route::post('post_comment/store', 'api\v1\Comment\CommentController@store');
	Route::get('post_comment/show/{id}', 'api\v1\Comment\CommentController@show');
	Route::delete('post_comment/delete/{id}', 'api\v1\Comment\CommentController@destroy');

	Route::get('user/bookmark_details', 'api\v1\Bookmark\BookmarkController@show');
	Route::post('post_user_activity/store', 'api\v1\UserActivity\UserActivityController@store');

	Route::get('user/gift_preference', 'api\v1\User\UserController@userGiftPreference');
	Route::put('user/update_gift_preference/{id}', 'api\v1\User\UserController@updateUserGiftPreference');

	Route::get('user/interest', 'api\v1\User\UserController@userInterests');
	Route::put('user/update_interest/{id}', 'api\v1\User\UserController@updateUserInterests');

	Route::get('user/upcoming_dates_details', 'api\v1\UpcomingDates\UpcomingDatesController@show');

});




