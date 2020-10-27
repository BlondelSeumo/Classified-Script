<?php

namespace App\Policies\Member;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
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
     * Check if user can send messages
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function send($user)
    {
        return $user->hasPermission('send_messages');
    }


    /**
     * Check if user can receive messages
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function receive(User $user)
    {
        $user->hasPermission('receive_messages');
    }


    /**
     * Check if user can delete his messages
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function delete($user)
    {
        return $user->hasPermission('delete_messages');
    }
}
