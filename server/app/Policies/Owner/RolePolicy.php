<?php

namespace App\Policies\Owner;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
     * Check if user can access roles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function acces($user)
    {
        return $user->hasPermission('access_roles');
    }


    /**
     * Check if user can create roles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function create($user)
    {
        return $user->hasPermission('create_roles');
    }


    /**
     * Check if user can edit roles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_roles');
    }


    /**
     * Check if user can delete roles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_roles');
    }
}
