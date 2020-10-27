<?php

namespace App\Http\Controllers\Main\Fetch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\Division;

class CitiesController extends Controller
{
    /**
	 * Get cities by state id
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function cities(Request $request)
    {
		// Validate state
		$request->validate([
			'state' => 'required|exists:world_divisions,id'
		]);	

		// Get cities
		$state = Division::find($request->state);

		return response($state->cities, 200);
    }
}
