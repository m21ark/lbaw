<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    use HandlesAuthorization;


    public function before(User $user, $ability)
    {
        if ($user->isAdmin !== null) {
            return true;
        }
    }

  
    public function viewAny(User $user)
    {
       return true; // before grantes that only admins can do this
    }

    
    public function create(User $user)
    {
        return Auth::check(); // it has to be auth to make a report
    }

   
    public function updateAny(User $user)
    {
        return true; // We already now its an admin .... before method
    }


    public function delete(User $user)
    {
        return true; // ITS A ADMIN
    }
}
