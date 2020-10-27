<?php

namespace App\Http\Controllers\Administrator\Users\Options;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-users');
    }



    /**
     * Block selected users
     * @param  Request $request [description]
     * @param  User    $users   [description]
     * @return [type]           [description]
     */
    public function block(Request $request, User $users)
    {
    	// Loop trough users
    	foreach ($request->users as $user) {
    		$users->isNotSuperAdmin()->whereUniqueId($user['unique_id'])->update([
				'isBlocked'  => true,
				'isActive'   => false,
				'isPending'  => false
    		]);
    	}

    	// Success
    	return response()->json([], 200);
    }
}
