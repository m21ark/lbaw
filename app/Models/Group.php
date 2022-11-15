<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $table = 'group';
    public $timestamps  = false;

    /*
    public function owners() {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
    */
}
