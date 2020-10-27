<?php

namespace App\Http\Controllers\Administrator\Roles;

use App\Http\Controllers\Controller;
use App\Model\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-roles');
    }


    /**
     * Get roles
     * @return [type] [description]
     */
    public function roles()
    {
    	// Get roles
        $roles = Role::latest()->paginate(50);

        return response()->json($roles, 200);
    }
}
