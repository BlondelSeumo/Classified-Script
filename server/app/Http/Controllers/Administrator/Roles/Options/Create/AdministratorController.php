<?php

namespace App\Http\Controllers\Administrator\Roles\Options\Create;

use App\Http\Controllers\Controller;
use App\Model\Role;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    	$this->authorize('owner-create-roles');
    }


    /**
     * Create admin role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
			'name'                  => 'required|max:60|unique:roles,name',
			'access_categories'     => 'boolean',
			'create_categories'     => 'boolean',
			'edit_categories'       => 'boolean',
			'delete_categories'     => 'boolean',
			'access_custom_fields'  => 'boolean',
			'create_custom_fields'  => 'boolean',
			'edit_custom_fields'    => 'boolean',
			'delete_custom_fields'  => 'boolean',
			'access_blog'           => 'boolean',
			'create_articles'       => 'boolean',
			'edit_articles'         => 'boolean',
			'delete_articles'       => 'boolean',
			'access_pages'          => 'boolean',
			'create_pages'          => 'boolean',
			'edit_pages'            => 'boolean',
			'delete_pages'          => 'boolean',
			'access_conversations'  => 'boolean',
			'access_chat'           => 'boolean',
			'access_users_messages' => 'boolean',
			'access_admin_messages' => 'boolean',
			'access_users'          => 'boolean',
			'approve_users'         => 'boolean',
			'edit_users'            => 'boolean',
			'delete_users'          => 'boolean',
			'create_users'          => 'boolean',
			'access_classifieds'    => 'boolean',
			'approve_classifieds'   => 'boolean',
			'edit_classifieds'      => 'boolean',
			'delete_classifieds'    => 'boolean',
			'classifieds_stats'     => 'boolean',
			'access_stores'         => 'boolean',
			'approve_stores'        => 'boolean',
			'edit_stores'           => 'boolean',
			'delete_stores'         => 'boolean',
			'access_comments'       => 'boolean',
			'approve_comments'      => 'boolean',
			'edit_comments'         => 'boolean',
			'delete_comments'       => 'boolean',
			'reported_comments'     => 'boolean'
    	]);	

    	// Generate permissions
        $permissions = $this->permissions($request);

        // Save role
        $role = Role::create([
            'unique_id'   => uniqueId(),
            'group'       => 'administrator',
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
			'access_categories'     => $request->get('access_categories'), 
			'create_categories'     => $request->get('create_categories'), 
			'edit_categories'       => $request->get('edit_categories'), 
			'delete_categories'     => $request->get('delete_categories'), 
			'access_custom_fields'  => $request->get('access_custom_fields'), 
			'create_custom_fields'  => $request->get('create_custom_fields'), 
			'edit_custom_fields'    => $request->get('edit_custom_fields'), 
			'delete_custom_fields'  => $request->get('delete_custom_fields'),
			'access_blog'           => $request->get('access_blog'), 
			'create_articles'       => $request->get('create_articles'), 
			'edit_articles'         => $request->get('edit_articles'), 
			'delete_articles'       => $request->get('delete_articles'), 
			'access_pages'          => $request->get('access_pages'), 
			'create_pages'          => $request->get('create_pages'), 
			'edit_pages'            => $request->get('edit_pages'), 
			'delete_pages'          => $request->get('delete_pages'), 
			'access_conversations'  => $request->get('access_conversations'), 
			'access_chat'           => $request->get('access_chat'), 
			'access_users_messages' => $request->get('access_users_messages'), 
			'access_admin_messages' => $request->get('access_admin_messages'), 
			'access_users'          => $request->get('access_users'), 
			'approve_users'         => $request->get('approve_users'), 
			'edit_users'            => $request->get('edit_users'), 
			'delete_users'          => $request->get('delete_users'), 
			'create_users'          => $request->get('create_users'), 
			'access_classifieds'    => $request->get('access_classifieds'), 
			'approve_classifieds'   => $request->get('approve_classifieds'), 
			'edit_classifieds'      => $request->get('edit_classifieds'), 
			'delete_classifieds'    => $request->get('delete_classifieds'), 
			'classifieds_stats'     => $request->get('classifieds_stats'),
			'access_stores'         => $request->get('access_stores'), 
			'approve_stores'        => $request->get('approve_stores'), 
			'edit_stores'           => $request->get('edit_stores'), 
			'delete_stores'         => $request->get('delete_stores'),
			'access_comments'       => $request->get('access_comments'), 
			'approve_comments'      => $request->get('approve_comments'), 
			'edit_comments'         => $request->get('edit_comments'), 
			'delete_comments'       => $request->get('delete_comments'), 
			'reported_comments'     => $request->get('reported_comments')
        );

        return json_encode($permissions, true);
    }
}
