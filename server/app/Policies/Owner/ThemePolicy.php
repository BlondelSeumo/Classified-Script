<?php

namespace App\Policies\Owner;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThemePolicy
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
     * Check if user has access to themes
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_themes');
    }


    /**
     * Check if user can edit themes
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_themes');
    }


    /**
     * Check if user can request themes from MendelManGroup
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function request($user)
    {
        return $user->hasPermission('request_themes');
    }
}
