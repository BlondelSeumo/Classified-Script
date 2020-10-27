<?php

namespace App\Http\Controllers\Administrator\Currencies\Options;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-delete-currencies');
    }


    /**
     * Delete selected currencies
     * @param  Request  $request    [description]
     * @param  Currency $currencies [description]
     * @return [type]               [description]
     */
    public function delete(Request $request, Currency $currencies)
    {
    	// Loop trough currencies
        foreach ($request->currencies as $currency) {
            $currencies->whereId($currency['id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
