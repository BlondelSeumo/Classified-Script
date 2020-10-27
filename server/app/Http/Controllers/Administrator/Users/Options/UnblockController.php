<?php

namespace App\Http\Controllers\Administrator\Users\Options;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class UnblockController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-users');
    }


    /**
     * Unblock users
     * @param  Request $request [description]
     * @param  User    $users   [description]
     * @return [type]           [description]
     */
    public function unblock(Request $request, User $users)
    {
    	// Loop trough users
    	foreach ($request->users as $user) {
    		$users->isNotSuperAdmin()->whereUniqueId($user['unique_id'])->update([
				'isBlocked'  => false,
				'isActive'   => true,
				'isPending'  => false
    		]);
    		$users->isNotSuperAdmin()->whereUniqueId($user['unique_id'])->restore();
    	}

    	// Success
    	return response()->json([], 200);
    }
}
