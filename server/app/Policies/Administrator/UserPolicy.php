<?php

namespace App\Policies\Administrator;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * Check if user has access to users
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_users');
    }


    /**
     * Check if user can create users
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function create($user)
    {
        return $user->hasPermission('create_users');
    }


    /**
     * Check if user can approve users
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function approve($user)
    {
        return $user->hasPermission('approve_users');
    }


    /**
     * Check if user can edit users
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_users');
    }


    /**
     * Check if user can delete users
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_users');
    }
}
