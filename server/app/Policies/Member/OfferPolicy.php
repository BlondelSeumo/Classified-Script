<?php

namespace App\Policies\Member;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
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
     * Check if user can make offers
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function send($user)
    {
        return $user->hasPermission('make_offers');
    }


    /**
     * Check if user can receive offers
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function receive($user)
    {
        return $user->hasPermission('receive_offers');
    }
}
