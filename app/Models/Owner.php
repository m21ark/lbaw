<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Owner extends Authenticatable
{

    public $timestamps  = false;
    protected $table = 'owner';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}
