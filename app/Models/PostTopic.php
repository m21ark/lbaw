<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTopic extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'post_topic';

    /*
    select "topic".*, "post".* from post_topic
    join "post" on id_post = "post".id
    join "topic" on id_topic = "topic".id;
    */

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\Topic', 'id_topic');
    }
}
