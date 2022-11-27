<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'birthdate', 'photo', 'bio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'id_poster')->orderBy('post_date', 'desc');
    }

    public function groupJoinRequests()
    {
        return $this->hasMany('App\Models\GroupJoinRequest', 'id_user');
    }

    public function groupsMember()
    {
        return $this->hasMany('App\Models\GroupJoinRequest', 'id_user')->where('acceptance_status', 'Accepted');
    }

    public function groupsOwner()
    {
        return $this->hasMany('App\Models\Owner', 'id_user');
    }
    
    public function interests()
    {
        return $this->hasMany('App\Models\TopicsInterestUser', 'id_user');
    }


    public function isAdmin() 
    {
        return $this->hasOne('App\Models\Admin', 'id_user');
    }
    
    public function reportsMade()
    {
        return $this->hasMany('App\Models\Report', 'id_reporter');
    }

    public function messagesSended()
    {
        return $this->hasMany('App\Models\Message', 'id_sender');
    }

    public function messagesReceived()
    {
        return $this->hasMany('App\Models\Message', 'id_receiver');
    }

    public function messages()
    {
        return $this->messagesSended->merge($this->messagesReceived)->sortBy('id');
    }

    public function getContactedUsers()
    {
        $messages = $this->messages()->sortByDesc('id');
        
        return $messages->unique(function ($item) {
            $max = $item['id_receiver'] > $item['id_sender'] ? $item['id_receiver'] : $item['id_sender'];
            $min = $item['id_receiver'] <= $item['id_sender'] ? $item['id_receiver'] : $item['id_sender'];
            return $max.$min;
        });
    }
}
