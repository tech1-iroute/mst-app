<?php
namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public function findForPassport($identifier) {
        return $this->orWhere('user_email', $identifier)->orWhere('user_mobile', $identifier)->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_user';
    protected $primaryKey = 'pid';
    protected $fillable = [
        'user_fname', 'user_lname', 'user_mname', 'user_email', 'password', 'dob', 'user_gender', 'user_mobile', 'user_code', 'MaritalStatus', 'user_image', 'facebook_id', 'google_id', 'face_user_name', 'user_like', 'user_interest', 'remember_token', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function posts()
        {
            return $this->hasMany('App\Post', 'uploaded_by_id');
            
        }

}
