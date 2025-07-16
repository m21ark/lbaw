<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'message';

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'id_sender');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'id_receiver');
    }
}
