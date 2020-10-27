<?php

namespace App\Http\Controllers\Administrator\Users\Options;

use App\Http\Controllers\Controller;
use App\Model\Plan;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-users');
    }


    /**
     * Edit user
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// Get user
    	$user = User::whereUsername($request->username)->first();

    	// Exists
    	if ($user) {

            // Get roles
            $roles     = Role::latest('name')->get();
            
            // Get plans
            $plans     = Plan::latest('title')->get();
            
            // Get countries
            $countries = Country::orderBy('name', 'asc')->get();

    		return response()->json([
                'user'      => $user,
                'roles'     => $roles,
                'plans'     => $plans,
                'countries' => $countries
            ], 200);
    	}

    	// Not found
    	return response()->json([], 400);

    }


    /**
     * Update user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Check if user exists
    	$user = User::whereUniqueId($request->id)->first();

    	// Not found
    	if (!$user) {
    		return response()->json([], 404);
    	}

    	// Make validation
    	$request->validate([
            'username' => 'required|max:60|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:60|unique:users,email,' . $user->id,
            'password' => 'nullable|max:60',
            'phone'    => 'nullable|max:60',
            'role'     => 'required|exists:roles,id',
            'plan'     => 'required|exists:plans,id',
            'country'  => 'nullable|exists:world_countries,id',
            'state'    => 'nullable|exists:world_divisions,id',
            'city'     => 'nullable|exists:world_cities,id',
    	]);

    	// Update user
    	$user->update([
            'username' => $request->username,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'plan_id'  => $request->plan,
            'role_id'  => $request->role
    	]);

    	// Update password
    	if ($request->password) {
    		$user->update([
    			'password' => bcrypt($request->password)
    		]);
    	}

        // Update country
        if ($request->country) {
            $user->update(['country' => $request->country]);
        }

        // Update state
        if ($request->state) {
            $user->update(['state' => $request->state]);
        }

        // Update city
        if ($request->city) {
            $user->update(['city' => $request->city]);
        }

    	// Update avatar
    	if ($request->hasFile('avatar')) {
            $user->uploadImage(request()->file('avatar'), 'avatar');
        }

        // Success
        return response()->json([], 200);
    }
}
