<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTopic extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'group_topic';
    public $incrementing = false; 

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'id_group');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\Topic', 'id_topic');
    }
}
