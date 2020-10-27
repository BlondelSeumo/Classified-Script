<?php

namespace App\Http\Controllers\Administrator\Geolocation\Cities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Khsing\World\Models\City;

class CitiesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-geo');
    }


    /**
     * Get all cities
     * @return [type] [description]
     */
    public function cities()
    {
    	// Get cities
		$cities = City::with('country', 'division')->latest('id')->paginate(300);

		return response()->json($cities, 200);
    }
}
