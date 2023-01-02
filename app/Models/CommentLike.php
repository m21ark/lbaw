<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;
    public $timestamps  = false;
    public $incrementing = false; 
    protected $table = 'like_comment';

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment', 'id_comment');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}
