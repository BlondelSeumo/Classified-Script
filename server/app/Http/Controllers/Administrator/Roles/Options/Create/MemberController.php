<?php

namespace App\Http\Controllers\Administrator\Roles\Options\Create;

use App\Http\Controllers\Controller;
use App\Model\Role;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-create-roles');
    }


    /**
     * Create memeber role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
        $request->validate([
            'name'                  => 'required|max:60|unique:roles,name',
            'write_comments'        => 'boolean',
            'edit_comments'         => 'boolean',
            'delete_comments'       => 'boolean',
            'send_messages'         => 'boolean',
            'receive_messages'      => 'boolean',
            'delete_messages'       => 'boolean',
            'report_classifieds'    => 'boolean',
            'report_comments'       => 'boolean',
            'make_offers'           => 'boolean',
            'receive_offers'        => 'boolean',
            'save_search'           => 'boolean',
            'follow_stores'         => 'boolean',
            'contact_stores'        => 'boolean',
            'see_advertisements'    => 'boolean',
            'see_classifieds_stats' => 'boolean'
        ]);

        // Generate permissions
        $permissions = $this->permissions($request);

        // Save role
        $role = Role::create([
            'unique_id'   => uniqueId(),
            'group'       => 'member',
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
            'write_comments'        => $request->get('write_comments'), 
            'edit_comments'         => $request->get('edit_comments'), 
            'delete_comments'       => $request->get('delete_comments'), 
            'send_messages'         => $request->get('send_messages'), 
            'receive_messages'      => $request->get('receive_messages'), 
            'delete_messages'       => $request->get('delete_messages'), 
            'report_classifieds'    => $request->get('report_classifieds'), 
            'report_comments'       => $request->get('report_comments'), 
            'make_offers'           => $request->get('make_offers'), 
            'receive_offers'        => $request->get('receive_offers'), 
            'save_search'           => $request->get('save_search'), 
            'follow_stores'         => $request->get('follow_stores'), 
            'contact_stores'        => $request->get('contact_stores'),
            'see_advertisements'    => $request->get('see_advertisements'), 
            'see_classifieds_stats' => $request->get('see_classifieds_stats')
        );

        return json_encode($permissions, true);
    }
}
