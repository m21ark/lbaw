<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTopic extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'post_topic';
    public $incrementing = false; 

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\Topic', 'id_topic');
    }
}
