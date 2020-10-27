<?php

namespace App\Http\Controllers\Administrator\Geolocation\Cities\Options;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\City;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-edit-geo');
    }


    /**
     * Update city
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// Check city
    	$city = City::whereId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
			'name'    => 'required|max:60',
			'country' => 'required|integer|exists:world_countries,id',
			'state'   => 'nullable|integer|exists:world_divisions,id'
    	]);

    	// Save city
		$city->name        = $request->name;
		$city->country_id  = $request->country;
		$city->division_id = $request->state;
    	$city->save();

    	// Success
    	return response($city->load('country', 'division'), 200);
    }
}
