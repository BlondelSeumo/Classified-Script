<?php

namespace App\Http\Controllers\Administrator\Currencies\Options;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-create-currencies');
    }

    

    /**
     * Insert currency in database
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Make validation
    	$request->validate([
			'name'   => 'required|max:60|unique:currencies,name',
			'code'   => 'required|max:3|min:3|unique:currencies,code',
			'locale' => 'required|max:10'
    	]);

    	// Insert currency
		$currency         = new Currency();
		$currency->name   = $request->name;
		$currency->code   = $request->code;
		$currency->locale = $request->locale;
    	$currency->save();

    	// Success
    	return response($currency, 200);
    }
}
