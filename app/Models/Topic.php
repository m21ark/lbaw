<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'topic';

    function group() {
        return $this->hasMany('App\Models\GroupTopic', 'id_topic');
    }
}
