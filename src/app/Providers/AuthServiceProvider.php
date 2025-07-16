<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Report;
use App\Policies\CommentPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      Post::class => PostPolicy::class,
      Group::class => GroupPolicy::class,
      Admin::class => AdminPolicy::class,
      Notification::class => NotificationPolicy::class,
      Comment::class => CommentPolicy::class,
      CommentLike::class => CommentPolicy::class,
      FriendsRequest::class => FriendsRequestPolicy::class,
      GroupJoinRequest::class => GroupJoinRequestPolicy::class,
      Like::class => PostPolicy::class,
      ProfileController::class => UserPolicy::class,
      Report::class => ReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
