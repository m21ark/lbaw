<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $table = 'like_comment';
    public $timestamps  = false;

    public function comment() {
        return $this->belongsTo('App\Models\Comment', 'id_comment');
    }
}
