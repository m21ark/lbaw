<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupJoinRequest extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'group_join_request';


    /*
        public function request()
    {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }
    */

    public function user() 
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}
