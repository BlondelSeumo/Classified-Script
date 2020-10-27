<?php

namespace App\Http\Controllers\Administrator\Geolocation\States\Options;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\Division;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-edit-geo');
    }


    /**
     * Edit state
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {

    	// Check state
    	$state = Division::whereId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
			'name'     => 'required|max:60',
			'country'  => 'required|integer|exists:world_countries,id',
			'has_city' => 'required|boolean'
    	]);

    	// Update state
    	$state->update([
			'name'       => $request->name,
			'country_id' => $request->country,
			'has_city'   => $request->has_city
    	]);

    	// Success
    	return response($state->load('country'), 200);
    }
}
