<?php

namespace App\Http\Controllers\Administrator\Roles\Options;

use App\Http\Controllers\Controller;
use App\Model\Role;
use Illuminate\Http\Request;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    	$this->authorize('owner-edit-roles');
    }


    /**
     * Get role to edit
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request, $id)
    {
    	// Get role
    	$role = Role::whereUniqueId($id)->firstOrFail();

    	return response($role, 201);
    }



    /**
     * Update role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Get role
    	$role              = Role::whereUniqueId($request->id)->firstOrFail();

    	// Validate request
    	$this->verify($request, $role);

    	// Generate permissions
		$permissions       = $this->permissions($request, $role);
		
		// Update role
		$role->name        = $request->name;
		$role->permissions = $permissions;
       	$role->save();

       	// Success
       	return response([], 200);

    }



    /**
     * Validate request
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function verify(Request $request, $role)
    {
    	// Check role group
    	switch ($role->group) {
    		case 'owner':
    			$request->validate([
					'name'                             => 'required|max:60|unique:roles,name,' . $role->id,
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
					'access_payouts'                   => 'boolean',
					'access_payment_gateways'          => 'boolean',
					'access_sms_services'              => 'boolean',
					'access_clouds'                    => 'boolean',
					'access_advertisements'            => 'boolean',
					'access_support'                   => 'boolean',
					'access_maintenance'               => 'boolean'
		    	]);
    			break;

    		case 'administrator':
    			$request->validate([
					'name'                  => 'required|max:60|unique:roles,name,' . $role->id,
					'access_categories'     => 'boolean',
					'create_categories'     => 'boolean',
					'edit_categories'       => 'boolean',
					'delete_categories'     => 'boolean',
					'access_custom_fields'  => 'boolean',
					'create_custom_fields'  => 'boolean',
					'edit_custom_fields'    => 'boolean',
					'delete_custom_fields'  => 'boolean',
					'access_coupons'        => 'boolean',
					'create_coupons'        => 'boolean',
					'edit_coupons'          => 'boolean',
					'delete_coupons'        => 'boolean',
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
					'access_products'       => 'boolean',
					'approve_products'      => 'boolean',
					'edit_products'         => 'boolean',
					'delete_products'       => 'boolean',
					'products_stats'        => 'boolean',
					'access_stores'         => 'boolean',
					'approve_stores'        => 'boolean',
					'edit_stores'           => 'boolean',
					'delete_stores'         => 'boolean',
					'stores_team'           => 'boolean',
					'access_comments'       => 'boolean',
					'approve_comments'      => 'boolean',
					'edit_comments'         => 'boolean',
					'delete_comments'       => 'boolean',
					'reported_comments'     => 'boolean',
					'access_orders'         => 'boolean',
					'edit_orders'           => 'boolean',
					'delete_orders'         => 'boolean'
		    	]);	
    			break;

    		case 'moderator':
    			$request->validate([
					'name'                => 'required|max:60|unique:roles,name,' . $role->id,
					'access_classifieds'  => 'boolean',
					'approve_classifieds' => 'boolean',
					'edit_classifieds'    => 'boolean',
					'delete_classifieds'  => 'boolean',
					'classifieds_stats'   => 'boolean',
					'access_stores'       => 'boolean',
					'approve_stores'      => 'boolean',
					'edit_stores'         => 'boolean',
					'delete_stores'       => 'boolean',
					'stores_team'         => 'boolean',
					'access_comments'     => 'boolean',
					'approve_comments'    => 'boolean',
					'edit_comments'       => 'boolean',
					'delete_comments'     => 'boolean',
					'reported_comments'   => 'boolean'
		    	]);	
    			break;

    		case 'member':
    			$request->validate([
		            'name'                  => 'required|max:60|unique:roles,name,' . $role->id,
		            'write_comments'        => 'boolean',
		            'edit_comments'         => 'boolean',
		            'delete_comments'       => 'boolean',
		            'send_messages'         => 'boolean',
		            'receive_messages'      => 'boolean',
		            'delete_messages'       => 'boolean',
		            'report_classifieds'    => 'boolean',
		            'report_products'       => 'boolean',
		            'report_comments'       => 'boolean',
		            'make_offers'           => 'boolean',
		            'receive_offers'        => 'boolean',
		            'save_search'           => 'boolean',
		            'follow_stores'         => 'boolean',
		            'contact_stores'        => 'boolean',
		            'use_coupons'           => 'boolean',
		            'see_advertisements'    => 'boolean',
		            'see_classifieds_stats' => 'boolean',
		            'use_watchlist'         => 'boolean'
		        ]);	
    			break;
    		
    		default:
    			return;
    			break;
    	}
    }



    /**
     * Generate permissions
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    protected function permissions(Request $request, $role)
    {
    	// Check group
    	switch ($role->group) {
    		case 'owner':
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
					'access_payouts'                   => $request->get('access_payouts'), 
					'access_payment_gateways'          => $request->get('access_payment_gateways'), 
					'access_sms_services'              => $request->get('access_sms_services'), 
					'access_clouds'                    => $request->get('access_clouds'),
					'access_advertisements'            => $request->get('access_advertisements'), 
					'access_support'                   => $request->get('access_support'), 
					'access_maintenance'               => $request->get('access_maintenance')
		        );
    			break;

    		case 'administrator':
    			$permissions = array(
					'access_categories'     => $request->get('access_categories'), 
					'create_categories'     => $request->get('create_categories'), 
					'edit_categories'       => $request->get('edit_categories'), 
					'delete_categories'     => $request->get('delete_categories'), 
					'access_custom_fields'  => $request->get('access_custom_fields'), 
					'create_custom_fields'  => $request->get('create_custom_fields'), 
					'edit_custom_fields'    => $request->get('edit_custom_fields'), 
					'delete_custom_fields'  => $request->get('delete_custom_fields'), 
					'access_coupons'        => $request->get('access_coupons'), 
					'create_coupons'        => $request->get('create_coupons'), 
					'edit_coupons'          => $request->get('edit_coupons'), 
					'delete_coupons'        => $request->get('delete_coupons'), 
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
					'access_products'       => $request->get('access_products'), 
					'approve_products'      => $request->get('approve_products'),
					'edit_products'         => $request->get('edit_products'), 
					'delete_products'       => $request->get('delete_products'), 
					'products_stats'        => $request->get('products_stats'), 
					'access_stores'         => $request->get('access_stores'), 
					'approve_stores'        => $request->get('approve_stores'), 
					'edit_stores'           => $request->get('edit_stores'), 
					'delete_stores'         => $request->get('delete_stores'), 
					'stores_team'           => $request->get('stores_team'), 
					'access_comments'       => $request->get('access_comments'), 
					'approve_comments'      => $request->get('approve_comments'), 
					'edit_comments'         => $request->get('edit_comments'), 
					'delete_comments'       => $request->get('delete_comments'), 
					'reported_comments'     => $request->get('reported_comments'), 
					'access_orders'         => $request->get('access_orders'), 
					'edit_orders'           => $request->get('edit_orders'), 
					'delete_orders'         => $request->get('delete_orders')
		        );
    			break;

    		case 'moderator':
    			$permissions = array(
					'access_classifieds'  => $request->get('access_classifieds'), 
					'approve_classifieds' => $request->get('approve_classifieds'), 
					'edit_classifieds'    => $request->get('edit_classifieds'), 
					'delete_classifieds'  => $request->get('delete_classifieds'), 
					'classifieds_stats'   => $request->get('classifieds_stats'),
					'access_stores'       => $request->get('access_stores'), 
					'edit_stores'         => $request->get('edit_stores'), 
					'delete_stores'       => $request->get('delete_stores'), 
					'stores_team'         => $request->get('stores_team'), 
					'approve_stores'      => $request->get('approve_stores'), 
					'access_comments'     => $request->get('access_comments'), 
					'edit_comments'       => $request->get('edit_comments'), 
					'approve_comments'    => $request->get('approve_comments'), 
					'delete_comments'     => $request->get('delete_comments'), 
					'reported_comments'   => $request->get('reported_comments'), 
		        );
    			break;

    		case 'member':
    			$permissions = array(
		            'write_comments'        => $request->get('write_comments'), 
		            'edit_comments'         => $request->get('edit_comments'), 
		            'delete_comments'       => $request->get('delete_comments'), 
		            'send_messages'         => $request->get('send_messages'), 
		            'receive_messages'      => $request->get('receive_messages'), 
		            'delete_messages'       => $request->get('delete_messages'), 
		            'report_classifieds'    => $request->get('report_classifieds'), 
		            'report_products'       => $request->get('report_products'), 
		            'report_comments'       => $request->get('report_comments'), 
		            'make_offers'           => $request->get('make_offers'), 
		            'receive_offers'        => $request->get('receive_offers'), 
		            'save_search'           => $request->get('save_search'), 
		            'follow_stores'         => $request->get('follow_stores'), 
		            'contact_stores'        => $request->get('contact_stores'), 
		            'use_coupons'           => $request->get('use_coupons'), 
		            'see_advertisements'    => $request->get('see_advertisements'), 
		            'see_classifieds_stats' => $request->get('see_classifieds_stats'), 
		            'use_watchlist'         => $request->get('use_watchlist'), 
		        );
    			break;
    		
    		default:
    			return;
    			break;
    	}
    	

        return json_encode($permissions, true);
    }
}
