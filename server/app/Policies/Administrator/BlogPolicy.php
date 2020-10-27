<?php

namespace App\Policies\Administrator;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPolicy
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
     * Check if user has access to blog
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_blog');
    }


    /**
     * Check if user can create articles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function create($user)
    {
        return $user->hasPermission('create_articles');
    }


    /**
     * Check if user can edit articles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_articles');
    }


    /**
     * Check if user can delete articles
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_articles');
    }
}
