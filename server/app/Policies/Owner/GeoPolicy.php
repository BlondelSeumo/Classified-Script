<?php

namespace App\Policies\Owner;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeoPolicy
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
     * Check if user has access to geolocation
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_geolocation');
    }


    /**
     * Check if user can create countries/states/cities
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function create($user)
    {
        return $user->hasPermission('create_geolocation');
    }


    /**
     * Check if user can edit countries/states/cities
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_geolocation');
    }


    /**
     * Check if user can delete countries/states/cities
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_geolocation');
    }
}
