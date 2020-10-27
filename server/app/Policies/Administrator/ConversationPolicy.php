<?php

namespace App\Policies\Administrator;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
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
     * Check if user can access conversations
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_conversations');
    }


    /**
     * Check if user can access chat
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function chat($user)
    {
        return $user->hasPermission('access_chat');
    }


    /**
     * Check if user can access users messages
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function users($user)
    {
        return $user->hasPermission('access_users_messages');
    }


    /**
     * Check if user can access admins messages
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function admins($user)
    {
        return $user->hasPermission('access_admin_messages');
    }
}
