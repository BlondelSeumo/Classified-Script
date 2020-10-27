<?php

namespace App\Http\Controllers\Manager\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use Illuminate\Http\Request;

class TimezonesController extends Controller
{
	function __construct()
	{
		$this->middleware(['auth:api', 'manager']);
	}


	/**
	 * Get timezones settings
	 * @return [type] [description]
	 */
    public function list()
    {
    	// Get currencies
    	$currencies = Currency::all();

    	// Get timezones
    	$timezones  = timezone_identifiers_list();

    	// Return data
    	return response()->json([
    		'currencies' => $currencies,
    		'timezones'  => $timezones
    	], 200);
    }
}
