<?php

namespace App\Policies\Moderator;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
        if ($user->isAdministrator()) {
            return true;
        }
    }


    /**
     * Check if user can access comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_comments');
    }


    /**
     * Check if user can approve comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function approve($user)
    {
        return $user->hasPermission('approve_comments');
    }


    /**
     * Check if user can edit comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_comments');
    }


    /**
     * Check if user delete comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_comments');
    }


    /**
     * Check if user can access reported comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function reported($user)
    {
        return $user->hasPermission('reported_comments');
    }
}
