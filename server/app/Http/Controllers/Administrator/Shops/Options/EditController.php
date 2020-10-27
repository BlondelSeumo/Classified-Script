<?php

namespace App\Http\Controllers\Administrator\Shops\Options;

use App\Http\Controllers\Controller;
use App\Model\Store;
use App\Model\User;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-shops');
    }



    /**
     * Edit shop
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// Check shop
		$shop      = Store::whereStore($request->shop)->firstOrFail();
		
		// Get countries 
		$countries = Country::oldest('name')->get();
		
		// Get users
		$users     = User::activated()->get();

    	// Response
    	return response([
			'shop'      => $shop,
			'countries' => $countries,
			'users'     => $users,
    	], 200);
    }



    /**
     * Update shop
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Get shop
    	$shop = Store::whereId($request->id)->firstOrFail();

    	// Validate request
    	$request->validate([
			'title'          => 'required|max:100|unique:stores,title,' . $shop->id,
			'subtitle'       => 'nullable|max:100',
			'description'    => 'nullable',
			'store'          => 'required|alpha_dash|max:100|unique:stores,store,' . $shop->id,
			'address1'       => 'nullable|max:100',
			'address2'       => 'nullable|max:100',
			'zip'            => 'nullable|max:30',
			'country'        => 'nullable|integer|exists:world_countries,id',
			'state'          => 'nullable|integer|exists:world_divisions,id',
			'city'           => 'nullable|integer|exists:world_cities,id',
			'customer_email' => 'nullable|max:60|email',
			'phone'          => 'nullable|max:60',
			'primary_locale' => 'required|in:en,fr,ar',
			'user'           => 'required|integer|exists:users,id',
    	]);

    	// Update shop
		$shop->user_id        = $request->user;
		$shop->title          = $request->title;
		$shop->subtitle       = $request->subtitle;
		$shop->description    = $request->description;
		$shop->store          = $request->store;
		$shop->address1       = $request->address1;
		$shop->address2       = $request->address2;
		$shop->zip            = $request->zip;
		$shop->country        = $request->country;
		$shop->state          = $request->state;
		$shop->city           = $request->city;
		$shop->customer_email = $request->customer_email;
		$shop->phone          = $request->phone;
		$shop->primary_locale = $request->primary_locale;
		$shop->isActive       = true;
		$shop->save();

    	// Success
    	return response($shop->store, 200);
    }
}
