<?php

namespace App\Policies\Member;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
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
     * Check if user can report deals
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function deals($user)
    {
        return $user->hasPermission('report_classifieds');
    }


    /**
     * Check if user can report comments
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function comments($user)
    {
        return $user->hasPermission('report_comments');
    }
}
