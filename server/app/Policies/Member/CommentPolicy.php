<?php

namespace App\Policies\Member;

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
        if ($user->isModerator()) {
            return true;
        }
    }


    /**
     * Check if user can write comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function write($user)
    {
        return $user->hasPermission('write_comments');
    }


    /**
     * Check if user can edit his comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function edit($user)
    {
        return $user->hasPermission('edit_comments');
    }


    /**
     * Check if user can delete his comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_comments');
    }
}
