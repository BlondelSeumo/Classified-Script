<?php

namespace App\Http\Controllers\Administrator\Currencies\Options;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use Illuminate\Http\Request;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-edit-currencies');
    }


    /**
     * Update currency
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// Fetch currency
    	$currency = Currency::whereId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
    		'name'   => 'required|max:60|unique:currencies,name,' . $currency->id,
			'code'   => 'required|max:3|min:3|unique:currencies,code,' . $currency->id,
			'locale' => 'required|max:10'
    	]);

    	// Update currency
		$currency->name   = $request->name;
		$currency->code   = $request->code;
		$currency->locale = $request->locale;
    	$currency->save();

    	// Success
    	return response($currency, 200);
    }
}
