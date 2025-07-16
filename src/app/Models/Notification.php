<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table = 'notification';

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'id_sender', 'id');
    }
}
