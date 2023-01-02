<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicsInterestUser extends Model
{
    use HasFactory;
    public $timestamps  = false;
    public $incrementing = false;
    protected $table = 'topics_interest_user';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\Topic', 'id_topic');
    }
}
