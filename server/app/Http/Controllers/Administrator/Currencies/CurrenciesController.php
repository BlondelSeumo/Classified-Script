<?php

namespace App\Http\Controllers\Administrator\Currencies;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Currency;

class CurrenciesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-currencies');
    }



    /**
     * Get currencies
     * @return [type] [description]
     */
    public function currencies()
    {
    	// Get currencies
    	$currencies = Currency::oldest('code')->withTrashed()->paginate(100);

    	return response()->json($currencies, 200);
    }
}
