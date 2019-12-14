<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComments extends Model
{
    //
    protected $table = 'comments';
    protected $primaryKey = 'cid';
    protected $fillable = [
        'comment', 'cpid', 'user_id', 'status', 'created_at', 'updated_at'
    ];



    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
        //return $this->belongsTo(User::class);
        return $this->belongsTo('App\User');
    }

}
