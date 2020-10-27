<?php

namespace App\Http\Controllers\Administrator\Fetch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Currency;

class CurrenciesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    }


    /**
     * Get currencies
     * @return [type] [description]
     */
    public function currencies()
    {
    	// Get currencies
    	$currencies = Currency::latest('name')->get();

    	return response($currencies, 200);
    }
}
