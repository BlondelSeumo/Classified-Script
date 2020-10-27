<?php

namespace App\Policies\Member;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SearchPolicy
{
    use HandlesAuthorization;

    /**
     * First admin has access to everything
     * @param  [type] $user    [description]
     * @param  [type] $ability [description]
     * @return [type]          [description]
     */
    public function before($user, $ability)
    {
        if ($user->isModerator()) {
            return true;
        }
    }


    /**
     * Check if user can save search
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function save($user)
    {
        return $user->hasPermission('save_search');
    }
}
