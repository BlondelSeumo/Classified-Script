<?php

namespace App\Policies\Member;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
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
     * Check if user can follow shops
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function follow($user)
    {
        return $user->hasPermission('follow_stores');
    }


    /**
     * Check if user can contact shops
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function contact($user)
    {
        return $user->hasPermission('contact_stores');
    }
}
