<?php

namespace App\Http\Controllers\Administrator\Users\Options;

use App\Http\Controllers\Controller;
use App\Model\Plan;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-create-users');
    }



    public function fetch()
    {
        // Get roles
        $roles     = Role::latest('name')->get();
        
        // Get plans
        $plans     = Plan::latest('title')->get();
        
        // Get countries
        $countries = Country::orderBy('name', 'asc')->get();

        return response([
            'roles' => $roles,
            'plans'     => $plans,
            'countries' => $countries
        ], 201);
    }



    /**
     * Create new user account
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate form
    	$request->validate([
			'username' => 'required|max:60|unique:users,username',
			'email'    => 'required|email|max:60|unique:users,email',
			'password' => 'required|max:60',
			'phone'    => 'nullable|max:60',
            'role'     => 'required|integer|exists:roles,id',
            'plan'     => 'required|integer|exists:plans,id',
            'country'  => 'nullable|integer|exists:world_countries,id',
            'state'    => 'nullable|integer|exists:world_divisions,id',
            'city'     => 'nullable|integer|exists:world_cities,id',
    	]);

    	// Create user
    	$user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'phone'    => $request->phone,
            'role_id'  => $request->role,
            'plan_id'  => $request->plan,
            'country'  => $request->country,
            'state'    => $request->state,
            'city'     => $request->city
    	]);

        // Check if request has avatar
        if ($request->hasFile('avatar')) {
            $user->uploadImage(request()->file('avatar'), 'avatar');
        }

    	// Success
    	return response()->json([], 200);
    }
}
