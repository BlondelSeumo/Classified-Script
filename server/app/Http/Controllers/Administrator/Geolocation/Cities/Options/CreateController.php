<?php

namespace App\Http\Controllers\Administrator\Geolocation\Cities\Options;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\City;
use Khsing\World\Models\Country;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-create-geo');
    }


    /**
     * Get countries
     * @return [type] [description]
     */
    public function fetch()
    {
    	// Get countries
    	$countries = Country::oldest('name')->get();

    	return response($countries, 200);
    }


    public function create(Request $request)
    {
    	// Make validation
    	$request->validate([
			'name'    => 'required|max:60',
			'country' => 'required|integer|exists:world_countries,id',
			'state'   => 'nullable|integer|exists:world_divisions,id'
    	]);

    	// Create city
		$city              = new City();
		$city->name        = $request->name;
		$city->country_id  = $request->country;
		$city->division_id = $request->state;
    	$city->save();

    	// Success
    	return response([], 200);
    }
}
