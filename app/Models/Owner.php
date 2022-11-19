<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    public $timestamps  = false;
    public $incrementing = false; // IMPORTANT: ADD THIS TO ASSOCIATIONS WITHOUT id
    protected $table = 'owner';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }


    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'id_group');
    }
}
