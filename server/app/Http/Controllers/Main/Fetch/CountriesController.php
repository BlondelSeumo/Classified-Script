<?php

namespace App\Http\Controllers\Main\Fetch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class CountriesController extends Controller
{

	/**
	 * Get all countries
	 * @return [type] [description]
	 */
    public function countries()
    {
    	// Get countries
    	$countries = Country::all();

		return response()->json($countries, 200);
    }
}
