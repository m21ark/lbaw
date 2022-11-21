<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'user_report';

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'id_post');
    }
    
    public function comment()
    {
        return $this->belongsTo('App\Models\Comment', 'id_comment');
    }
}
