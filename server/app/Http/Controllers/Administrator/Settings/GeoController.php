<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use App\Model\SettingsGeo;
use Illuminate\Http\Request;
use Khsing\World\Models\Country;

class GeoController extends Controller
{
   	function __construct()
   	{
   		$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-geo');
   	}


   	/**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get settings
        $settings   = SettingsGeo::firstOrFail();
        
        // Get countries
        $countries  = Country::oldest('name')->get();
        
        // Get currencies
        $currencies = Currency::latest('name')->get();

        // Show settings
        return response()->json([
	            'settings'   => $settings,
	            'countries'  => $countries,
	            'currencies' => $currencies,
	        ], 200); 
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Validate request
        $request->validate([
            'multiple_countries' => 'required|boolean',
            'default_country'    => 'required|exists:world_countries,id',
            'default_state'      => 'nullable|exists:world_divisions,id',
            'default_city'       => 'required|exists:world_cities,id',
            'default_currency'   => 'required|exists:currencies,id',
            'enable_states'      => 'required|boolean',
            'enable_cities'      => 'required|boolean'
        ]); 
        
        // Update settings
        SettingsGeo::where('id', 1)->update([
            'isMultipleCountries' => $request->multiple_countries,
            'defaultCountry'      => $request->default_country,
            'defaultState'        => $request->default_state,
            'defaultCity'         => $request->default_city,
            'defaultCurrency'     => $request->default_currency,
            'enableStates'        => $request->enable_states,
            'enableCities'        => $request->enable_cities
        ]);

        // Success
        return response()->json([], 200);
    }
}
