<?php

namespace App\Http\Controllers\Administrator\Geolocation\States\Options;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;
use Khsing\World\Models\Division;

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


    /**
     * Insert state in our records
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Make validation
    	$request->validate([
			'name'       => 'required|max:60',
			'country'    => 'required|integer|exists:world_countries,id',
			'has_cities' => 'required|boolean'
    	]);

    	// Create new state
		$state             = new Division();
		$state->name       = $request->name;
		$state->country_id = $request->country;
		$state->has_city   = $request->has_cities;
    	$state->save();

    	// Success
    	return response([], 200);
    }
}
