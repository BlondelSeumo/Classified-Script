<?php

namespace App\Http\Controllers\Administrator\Currencies\Options;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-delete-currencies');
    }


    /**
     * Restore selected currencies
     * @param  Request  $request    [description]
     * @param  Currency $currencies [description]
     * @return [type]               [description]
     */
    public function restore(Request $request, Currency $currencies)
    {
    	// Loop trough currencies
        foreach ($request->currencies as $currency) {
            $currencies->whereId($currency['id'])->restore();
        }

        // Success
        return response()->json([], 200);
    }
}
