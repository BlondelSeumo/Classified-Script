<?php

namespace App\Http\Controllers\Administrator\Pending;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-approve-users');
    }


    /**
     * Get all pending users
     * @return [type] [description]
     */
    public function users()
    {
    	// Get pending users
    	$users = User::where('isPending', true)->latest()->paginate(50);

    	return response($users);
    }
}
