<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\Plan;
use App\Model\Role;
use App\Model\SettingsAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-auth');
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get settings
    	$settings = SettingsAuth::firstOrFail();

        // Get roles
        $roles    = Role::latest('name')->get();

        // Get plans
        $plans    = Plan::latest()->get();

    	// Show settings
    	return response([
            'settings' => $settings,
            'roles'    => $roles,
            'plans'    => $plans
        ], 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Make validation
    	$request->validate([
            'need_activation'      => 'required|boolean',
            'activation_type'      => 'required|in:email,manually',
            'activation_code_life' => 'required|integer',
            'max_attempts'         => 'required|integer',
            'retry_after'          => 'required|integer',
            'default_role'         => 'required|integer|exists:roles,id',
            'default_plan'         => 'required|integer|exists:plans,id',
            'enable_registration'  => 'required|boolean',
            'login_by'             => 'required|in:username,email,phone,ue,uep'
    	]);

    	// Update settings
    	SettingsAuth::find(1)->update([
            'needActivation'           => request('need_activation'),
            'activationType'           => request('activation_type'),
            'activation_expires_after' => request('activation_code_life'),
            'maxAttempts'              => request('max_attempts'),
            'retries_in'               => request('retry_after'),
            'isRegister'               => request('enable_registration'),
            'default_role_id'          => request('default_role'),
            'default_plan_id'          => request('default_plan'),
            'loginBy'                  => request('login_by')
    	]);

    	// Success
    	return response([]);
    }
}
