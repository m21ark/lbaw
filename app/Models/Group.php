<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;
    protected $table = 'group';
    public $timestamps  = false;


    public function owners()
    {
        return $this->hasMany('App\Models\Owner', 'id_group');
    }


    public function members()
    {
        return $this->hasMany('App\Models\GroupJoinRequest', 'id_group')->where('acceptance_status', 'Accepted');
    }

    public function posts(){
        return $this->hasMany('App\Models\Post', 'id_group');
    }
}
