<?php

namespace App\Http\Controllers\Administrator\Deals\Packages\Options;

use App\Http\Controllers\Controller;
use App\Model\ClassifiedPackage;
use App\Model\Currency;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-create-packages');
    }



    /**
     * Create new deal package
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
			'title'    => 'required|max:60',
			'slug'     => 'required|max:60|unique:classifieds_packages,slug',
			'type'     => 'required|in:featured,urgent,highlight',
			'days'     => 'required|integer',
			'price'    => 'required|max:100',
			'currency' => 'required|exists:currencies,code',
			'discount' => 'nullable|integer|between:1,100'
    	]);

    	// Create new package
    	$package = ClassifiedPackage::create([
			'unique_id' => uniqueId(),
			'title'     => $request->title,
			'slug'      => $request->slug,
			'type'      => $request->type,
			'days'      => $request->days,
			'price'     => $request->price,
			'currency'  => $request->currency,
			'locale'    => $this->locale($request->currency),
			'discount'  => $request->discount
    	]);

    	// Success
    	return response([]);
    }



    /**
     * Get currency locale
     * @param  [type] $currency [description]
     * @return [type]           [description]
     */
    private function locale($code)
    {
    	// Get locale
        $currency = Currency::whereCode($code)->first();

        return $currency->locale;
    }
}
