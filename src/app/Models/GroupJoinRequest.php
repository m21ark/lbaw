<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupJoinRequest extends Model
{
    use HasFactory;
    public $timestamps  = false;
    public $incrementing = false;
    protected $table = 'group_join_request';


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'id_group');
    }
}
