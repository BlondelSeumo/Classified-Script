<?php

namespace App\Http\Controllers\Administrator\Roles\Options;

use App\Http\Controllers\Controller;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    	$this->authorize('owner-delete-roles');
    }


    /**
     * Delete this role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request)
    {
    	// Make validation
		$request->validate([
			'role' => 'required|exists:roles,unique_id',
			'next' => 'required|exists:roles,unique_id'
		]);

		// Delete role
		$role    = Role::where('unique_id', $request->role)->first();
		$newRole = Role::where('unique_id', $request->next)->first();
		$role->delete();

		// Update users roles
		User::where('role_id', $role->id)->update([
			'role_id' => $newRole->id
		]);

		// Success
		return response()->json([], 200);
    }
}
