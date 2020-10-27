<?php

namespace App\Policies\Administrator;

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
        if ($user->isOwner()) {
            return true;
        }
    }


    /**
     * Check if user can access shops
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_stores');
    }


    /**
     * Check if user can approve shops
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function approve($user)
    {
        return $user->hasPermission('approve_stores');
    }


    /**
     * Check if user can edit shops
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_stores');
    }


    /**
     * Check if user can delete shops
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_stores');
    }
}
