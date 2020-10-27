<?php

namespace App\Http\Controllers\Main\Fetch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class StatesController extends Controller
{
    /**
	 * Get states by country id
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function states(Request $request)
    {
		// Validate country
		$request->validate([
			'country' => 'required|exists:world_countries,id'
		]);	

		// Get states
		$country = Country::find($request->country);

		return response([
			'states' => $country->divisions,
			'cities' => $country->cities,
		], 200);
    }
}
