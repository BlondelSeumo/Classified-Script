<?php

namespace App\Http\Controllers\Administrator\Membership\Plans\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Currency;
use App\Model\Plan;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-edit-packages');
    }


    /**
     * Edit plan
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// Check plan
    	$plan = Plan::whereUniqueId($request->id)->firstOrFail();

    	// Get currencies
    	$currencies = Currency::latest('name')->get();

    	return response([
			'plan'       => $plan,
			'currencies' => $currencies
    	], 200);
    }


    public function update(Request $request)
    {
    	// check plan
    	$plan = Plan::whereUniqueId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
    		'title'                         => 'required|max:60',
            'subtitle'                      => 'required|max:60',
            'description'                   => 'max:100',
            'slug'                          => 'required|max:60|unique:plans,slug,' .$plan->id,
            'price'                         => 'required|max:100',
            'discount'                      => 'nullable|integer|between:1,100',
            'price'                         => 'nullable|max:60',
            'currency'                      => 'nullable|exists:currencies,code',
            'frequency'                     => 'nullable|in:daily,weekly,monthly,yearly',
            'recommended'                   => 'boolean',
            'enable_stores'                 => 'boolean',
            'enable_autoshare'              => 'boolean',
            'maximum_stores'                => 'nullable|integer',
            'maximum_classifieds'           => 'nullable|integer',
            'maximum_classifieds_images'    => 'nullable|integer',
            'classifieds_expiration_period' => 'nullable|integer'
    	]);

    	// Update plan
    	$plan->update([
            'title'                    => request('title'),
            'subtitle'                 => request('subtitle'),
            'description'              => request('description'),
            'slug'                     => request('slug'),
            'price'                    => request('price'),
            'currency'                 => request('currency'),
            'locale'                   => $this->locale($request->currency),
            'frequency'                => request('frequency'),
            'discount'                 => request('discount'),
            'isRecommended'            => $request->get('recommended'),
            'isStores'                 => $request->get('enable_stores'),
            'isAutoshare'              => $request->get('enable_autoshare'),
            'stores_limit'             => request('maximum_stores'),
            'classifieds_limit'        => request('maximum_classifieds'),
            'classifieds_images_limit' => request('maximum_classifieds_images'),
            'classifieds_expiry'       => request('classifieds_expiration_period'),
    	]);

        // Upload plan icon if exist
        if ($request->hasFile('icon')) {
            $plan->uploadImage(request()->file('icon'), 'icon');
        }

        // Success
        return response()->json([], 200);
    }


    /**
     * Get currency locale
     * @param  [type] $currency [description]
     * @return [type]           [description]
     */
    public function locale($currency = null)
    {

        if (!is_null($currency)) {
            // Get locale
            $locale = Currency::where('code', $currency)->first();

            return $locale->locale;
        }

        return null;
    }
}
