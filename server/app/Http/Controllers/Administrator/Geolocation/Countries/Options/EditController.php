<?php

namespace App\Http\Controllers\Administrator\Geolocation\Countries\Options;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-edit-geo');
    }


    /**
     * Update country
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {

    	// Check country
    	$country = Country::whereId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
			'name'         => 'required|max:60|unique:world_countries,name,' . $country->id,
			'capital'      => 'required|max:60',
			'has_division' => 'required|boolean'
    	]);

    	// Update country
    	$country->update([
			'name'         => $request->name,
			'capital'      => $request->capital,
			'has_division' => $request->has_division
    	]);

    	// Success
    	return response($country, 200);
    }
}
