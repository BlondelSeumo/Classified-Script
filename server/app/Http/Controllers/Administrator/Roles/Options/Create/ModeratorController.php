<?php

namespace App\Http\Controllers\Administrator\Roles\Options\Create;

use App\Http\Controllers\Controller;
use App\Model\Role;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    	$this->authorize('owner-create-roles');
    }


    /**
     * Create moderator role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
			'name'                => 'required|max:60|unique:roles,name',
			'access_classifieds'  => 'boolean',
			'approve_classifieds' => 'boolean',
			'edit_classifieds'    => 'boolean',
			'delete_classifieds'  => 'boolean',
			'classifieds_stats'   => 'boolean',
			'access_stores'       => 'boolean',
			'approve_stores'      => 'boolean',
			'edit_stores'         => 'boolean',
			'delete_stores'       => 'boolean',
			'access_comments'     => 'boolean',
			'approve_comments'    => 'boolean',
			'edit_comments'       => 'boolean',
			'delete_comments'     => 'boolean',
			'reported_comments'   => 'boolean'
    	]);	

    	// Generate permissions
        $permissions = $this->permissions($request);

        // Save role
        $role = Role::create([
            'unique_id'   => uniqueId(),
            'group'       => 'moderator',
            'name'        => request('name'),
            'permissions' => $permissions
        ]);

        // Role created successfully
        return response()->json([], 200);
    }


    /**
     * Generate permissions
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    protected function permissions(Request $request)
    {
    	$permissions = array(
			'access_classifieds'  => $request->get('access_classifieds'), 
			'approve_classifieds' => $request->get('approve_classifieds'), 
			'edit_classifieds'    => $request->get('edit_classifieds'), 
			'delete_classifieds'  => $request->get('delete_classifieds'), 
			'classifieds_stats'   => $request->get('classifieds_stats'),
			'access_stores'       => $request->get('access_stores'), 
			'edit_stores'         => $request->get('edit_stores'), 
			'delete_stores'       => $request->get('delete_stores'), 
			'approve_stores'      => $request->get('approve_stores'), 
			'access_comments'     => $request->get('access_comments'), 
			'edit_comments'       => $request->get('edit_comments'), 
			'approve_comments'    => $request->get('approve_comments'), 
			'delete_comments'     => $request->get('delete_comments'), 
			'reported_comments'   => $request->get('reported_comments'), 
        );

        return json_encode($permissions, true);
    }
}
