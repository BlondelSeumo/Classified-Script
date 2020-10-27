<?php

namespace App\Policies\Member;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertisementPolicy
{
    use HandlesAuthorization;


    /**
     * Check if user can see Google Adsense ads
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function see($user)
    {
        return $user->hasPermission('see_advertisements');
    }
}
