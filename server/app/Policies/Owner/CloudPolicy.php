<?php

namespace App\Policies\Owner;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CloudPolicy
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
        if ($user->isSuperAdmin()) {
            return true;
        }
    }


    /**
     * Check if current owner has access to clouds settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_clouds');
    }
}
