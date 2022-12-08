<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendsRequest extends Model
{
    use HasFactory;
    public $timestamps  = false;
    public $incrementing = false;
    protected $table = 'friend_request';


    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'id_user_receiver');
    }

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'id_user_sender');
    }
}
