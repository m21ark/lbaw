<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'post';

    public function owner() {
        return $this->belongsTo('App\Models\User', 'id_poster');
    }

    public function likes() {
        return $this->hasMany('App\Models\Like', 'id_post');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment', 'id_post');
    }


    // Don't add create and update timestamps in database.
    public $timestamps  = false;
}
