<?php

namespace App\Http\Controllers\Administrator\Users;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-users');
    }


    /**
     * Get users
     * @return [type] [description]
     */
    public function users()
    {
    	$users = User::with('role', 'plan', 'country', 'state', 'city')->withTrashed()->latest()->paginate(8);

    	return response()->json($users, 200);
    }
}
