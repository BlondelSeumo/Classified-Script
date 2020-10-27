<?php

namespace App\Http\Controllers\Administrator\Roles\Options\Create;

use App\Http\Controllers\Controller;
use App\Model\Role;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    	$this->authorize('owner-create-roles');
    }


    /**
     * Create new owner role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
			'name'                             => 'required|max:60|unique:roles,name',
			'access_plans'                     => 'boolean',
			'create_plans'                     => 'boolean',
			'edit_plans'                       => 'boolean',
			'delete_plans'                     => 'boolean',
			'access_currencies'                => 'boolean',
			'create_currencies'                => 'boolean',
			'edit_currencies'                  => 'boolean',
			'delete_currencies'                => 'boolean',
			'access_geolocation'               => 'boolean',
			'create_geolocation'               => 'boolean',
			'edit_geolocation'                 => 'boolean',
			'delete_geolocation'               => 'boolean',
			'access_roles'                     => 'boolean',
			'create_roles'                     => 'boolean',
			'edit_roles'                       => 'boolean',
			'delete_roles'                     => 'boolean',
			'access_themes'                    => 'boolean',
			'edit_themes'                      => 'boolean',
			'request_themes'                   => 'boolean',
			'access_settings'                  => 'boolean',
			'access_general_settings'          => 'boolean',
			'access_auth_settings'             => 'boolean',
			'access_smtp_settings'             => 'boolean',
			'access_security_settings'         => 'boolean',
			'access_geo_settings'              => 'boolean',
			'access_seo_settings'              => 'boolean',
			'access_uploading_settings'        => 'boolean',
			'access_payment_gateways_settings' => 'boolean',
			'access_social_media_settings'     => 'boolean',
			'access_watermark_settings'        => 'boolean',
			'access_footer_settings'           => 'boolean',
			'access_users_subscriptions'       => 'boolean',
			'access_payment_gateways'          => 'boolean',
			'access_sms_services'              => 'boolean',
			'access_clouds'                    => 'boolean',
			'access_advertisements'            => 'boolean',
			'access_support'                   => 'boolean',
			'access_maintenance'               => 'boolean'
    	]);	

    	// Generate permissions
        $permissions = $this->permissions($request);

        // Save role
        $role = Role::create([
            'unique_id'   => uniqueId(),
            'group'       => 'owner',
            'name'        => $request->name,
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
			'access_plans'                     => $request->get('access_plans'), 
			'create_plans'                     => $request->get('create_plans'), 
			'edit_plans'                       => $request->get('edit_plans'), 
			'delete_plans'                     => $request->get('delete_plans'), 
			'access_currencies'                => $request->get('access_currencies'), 
			'create_currencies'                => $request->get('create_currencies'), 
			'edit_currencies'                  => $request->get('edit_currencies'), 
			'delete_currencies'                => $request->get('delete_currencies'), 
			'access_geolocation'               => $request->get('access_geolocation'), 
			'create_geolocation'               => $request->get('create_geolocation'), 
			'edit_geolocation'                 => $request->get('edit_geolocation'), 
			'delete_geolocation'               => $request->get('delete_geolocation'), 
			'access_roles'                     => $request->get('access_roles'), 
			'create_roles'                     => $request->get('create_roles'), 
			'edit_roles'                       => $request->get('edit_roles'), 
			'delete_roles'                     => $request->get('delete_roles'), 
			'access_themes'                    => $request->get('access_themes'), 
			'edit_themes'                      => $request->get('edit_themes'), 
			'request_themes'                   => $request->get('request_themes'), 
			'access_settings'                  => $request->get('access_settings'), 
			'access_general_settings'          => $request->get('access_general_settings'), 
			'access_auth_settings'             => $request->get('access_auth_settings'), 
			'access_smtp_settings'             => $request->get('access_smtp_settings'), 
			'access_security_settings'         => $request->get('access_security_settings'), 
			'access_geo_settings'              => $request->get('access_geo_settings'), 
			'access_seo_settings'              => $request->get('access_seo_settings'), 
			'access_uploading_settings'        => $request->get('access_uploading_settings'), 
			'access_payment_gateways_settings' => $request->get('access_payment_gateways_settings'), 
			'access_social_media_settings'     => $request->get('access_social_media_settings'), 
			'access_watermark_settings'        => $request->get('access_watermark_settings'), 
			'access_footer_settings'           => $request->get('access_footer_settings'), 
			'access_users_subscriptions'       => $request->get('access_users_subscriptions'),
			'access_payment_gateways'          => $request->get('access_payment_gateways'), 
			'access_sms_services'              => $request->get('access_sms_services'), 
			'access_clouds'                    => $request->get('access_clouds'),
			'access_advertisements'            => $request->get('access_advertisements'), 
			'access_support'                   => $request->get('access_support'), 
			'access_maintenance'               => $request->get('access_maintenance')
        );

        return json_encode($permissions, true);
    }
}
