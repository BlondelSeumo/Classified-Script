<?php

namespace App\Http\Controllers\Administrator\Geolocation\Countries;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Khsing\World\Models\Country;

class CountriesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-geo');
    }



    /**
     * Get countries
     * @return [type] [description]
     */
    public function countries()
    {
    	// Get countries
    	$countries = Country::with('continent')->latest('name')->paginate(50);

    	return response()->json($countries, 200);
    }
}
