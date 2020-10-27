<?php

namespace App\Http\Controllers\Administrator\Geolocation\Countries\Options;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-create-geo');
    }


    /**
     * Get all continents
     * @return [type] [description]
     */
    public function fetch()
    {
    	// Get continents
    	$continents = Continent::all();

    	return response($continents, 200);
    }


    /**
     * Insert country in our records
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Make validation
    	$request->validate([
			'name'        => 'required|max:60|unique:world_countries,name',
			'continent'   => 'required|exists:world_continents,id',
			'code'        => 'required|max:2|min:2|unique:world_countries,code',
			'flag'        => 'image|max:5000|mimes:jpg,png,jpeg',
			'has_states'  => 'required|boolean',
			'capital'     => 'required|max:60',
			'callingcode' => 'required|integer'
    	]);

    	// Create country
		$country               = new Country();
		$country->name         = $request->name;
		$country->full_name    = $request->name;
		$country->capital      = $request->capital;
		$country->continent_id = $request->continent;
		$country->code         = $request->code;
		$country->has_division = $request->has_states;
		$country->callingcode  = $request->callingcode;
    	$country->save();

    	// Save flag image
    	$this->saveFlag($request->file('flag'), $request->code);

    	// Success
    	return response([], 200);
    }


    /**
     * Save country flag image
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    private function saveFlag($flag, $code)
    {
    	// Get path
    	$path = public_path('images/flags/large/' . $code . '.png');

    	// Check if image already exists
    	if (file_exists($path)) {
    		return;
    	}

    	// open an image file
		$img = Image::make($flag);

		// now you are able to resize the instance
		$img->resize(80, 80);

		// finally we save the image as a new file
		$img->save($path);

		return;
    }
}
