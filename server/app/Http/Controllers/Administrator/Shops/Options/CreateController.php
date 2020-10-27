<?php

namespace App\Http\Controllers\Administrator\Shops\Options;

use App\Http\Controllers\Controller;
use App\Model\Store;
use App\Model\User;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-shops');
    }


    /**
     * Get countries and users
     * @return [type] [description]
     */
    public function fetch()
    {
    	// Get users
		$users     = User::activated()->get();
		
		// Get countries
		$countries = Country::oldest('name')->get();

    	return response([
			'users'     => $users,
			'countries' => $countries
    	]);
    }



    /**
     * Create new shop
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
			'title'          => 'required|max:100|unique:stores,title',
			'subtitle'       => 'nullable|max:100',
			'description'    => 'nullable',
			'store'          => 'required|alpha_dash|max:100|unique:stores,store',
			'address1'       => 'nullable|max:100',
			'address2'       => 'nullable|max:100',
			'zip'            => 'nullable|max:30',
			'country'        => 'nullable|integer|exists:world_countries,id',
			'state'          => 'nullable|integer|exists:world_divisions,id',
			'city'           => 'nullable|integer|exists:world_cities,id',
			'customer_email' => 'nullable|max:60|email',
			'phone'          => 'nullable|max:60',
			'primary_locale' => 'required|in:en,fr,ar',
			'user'           => 'required|exists:users,id',
    	]);

    	// Save store
    	$store = Store::create([
			'unique_id'      => uniqueId(),
			'user_id'        => $request->user,
			'title'          => $request->title,
			'subtitle'       => $request->subtitle,
			'description'    => $request->description,
			'store'          => $request->store,
			'address1'       => $request->address1,
			'address2'       => $request->address2,
			'zip'            => $request->zip,
			'country'        => $request->country,
			'state'          => $request->state,
			'city'           => $request->city,
			'customer_email' => $request->customer_email,
			'phone'          => $request->phone,
			'primary_locale' => $request->primary_locale,
			'isActive'       => true
    	]);

    	// Send notification to user
    	// Tell him that new store has been created
    	// If admin is the owner, no need for this action :)
    	$this->sendNotification($request);

    	// Success
    	return response([], 200);
    }



    /**
     * Send notification to user if not admin
     * about new store
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendNotification(Request $request)
    {
    	//
    }
}
