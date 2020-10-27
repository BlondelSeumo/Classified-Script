<?php

namespace App\Policies\Administrator;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealPolicy
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
        if ($user->isOwner()) {
            return true;
        }
    }


    /**
     * Check if user has access to deals
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_classifieds');
    }


    /**
     * Check if user can approve deals
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function approve($user)
    {
        return $user->hasPermission('approve_classifieds');
    }


    /**
     * Check if user can edit deals
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_classifieds');
    }


    /**
     * Check if user can delete deals
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_classifieds');
    }


    /**
     * Check if user can access stats
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function statistics($user)
    {
        return $user->hasPermission('classifieds_stats');
    }
}
