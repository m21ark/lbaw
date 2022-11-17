<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'like_post';


    public function post() {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }
}
