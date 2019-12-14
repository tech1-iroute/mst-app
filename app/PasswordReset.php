<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    //
    public function findForPassport($identifier) {
        return $this->orWhere('user_email', $identifier)->first();
    }
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $fillable = [
         'user_email', 'token', 'created_at', 'updated_at'
    ];
}
