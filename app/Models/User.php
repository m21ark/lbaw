<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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
        'username', 'email', 'password', 'birthdate', 'photo', 'bio', 'ban_date', 'visibility'
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
        return $this->hasMany('App\Models\GroupJoinRequest', 'id_user')->where('acceptance_status', 'Accepted')->whereNotIn('id_group', $this->groupsOwner->pluck('id_group')->toArray());
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
            return $max . $min;
        });
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'id_user');
    }

    public function friendsRequests()
    {
        return $this->hasMany('App\Models\FriendsRequest', 'id_user_receiver');
    }

    public function pendentFriendsRequests()
    {
        return $this->hasMany('App\Models\FriendsRequest', 'id_user_receiver')->where('acceptance_status', 'Pendent');
    }

    public function sendingRequests()
    {
        return $this->hasMany('App\Models\FriendsRequest', 'id_user_sender');
    }

    public function friends()
    {
        return $this->friendsRequests->concat($this->sendingRequests)->where('acceptance_status', 'Accepted');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'id_commenter');
    }

    public function like_in_comments()
    {
        return $this->hasMany('App\Models\CommentLike', 'id_user');
    }

    public function like_in_post()
    {
        return $this->hasMany('App\Models\Like', 'id_user');
    }

    public function isOwnerOfGroup($id_group)
    {
        return $this->groupsOwner->contains('id_group', $id_group);
    }

    public function removeOwner($id_group)
    {
        $this->groupsOwner()->where('id_group', $id_group)->delete();
    }


    public function topics_names()
    {
        return $this->belongsToMany('App\Models\Topic', 'topics_interest_user', 'id_user', 'id_topic')->select('name');
    }

}
