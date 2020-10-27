<?php

namespace App\Http\Controllers\Manager\Settings;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use App\Model\ManagerSettingsGeneral;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
   	function __construct()
   	{
   		$this->middleware(['auth:api', 'manager']);
   	}



   	/**
   	 * Get general settings
   	 * @return [type] [description]
   	 */
   	public function settings()
   	{
   		// Get settings
   		$settings   = ManagerSettingsGeneral::firstOrCreate([
   			'user_id' => auth()->id()
   		]);

   		// Get currencies
    	$currencies = Currency::all();

    	// Get timezones
    	$timezones  = timezone_identifiers_list();

    	// Return data
    	return response()->json([
			'settings'   => $settings,
			'currencies' => $currencies,
			'timezones'  => $timezones
    	], 200);
   	}



   	/**
   	 * Update general settings
   	 * @param  Request $request [description]
   	 * @return [type]           [description]
   	 */
   	public function update(Request $request)
   	{
   		// Validate Request
   		$request->validate([
			'timezone'    => 'required|max:20',
			'currency'    => 'required|exists:currencies,code',
			'prefix'      => 'nullable|max:20',
			'suffix'      => 'nullable|max:20',
			'weight_unit' => 'required|in:lb,oz,mg,g,kg',
			'length_unit' => 'required|in:mm,cm,m,in',
			'volume_unit' => 'required|in:gal,ml,l,dl'
   		]);

   		// Check if timezone exist
   		if (!in_array($request->timezone, timezone_identifiers_list())) {
   				
   			// Timezone not found
   			return response()->json([
   				'failed'  => true,
   				'message' => 'Oops! Selected timezone could not be found. Please try again'
   			], 422);

   		}

   		// Update settings
		$settings                          = ManagerSettingsGeneral::updateOrCreate([
		'user_id' => auth()->id()
		]);
		$settings->default_currency        = $request->currency;
		$settings->default_currency_locale = $this->currencyLocale($request->currency);
		$settings->default_timezone        = $request->timezone;
		$settings->default_order_prefix    = $request->prefix;
		$settings->default_order_suffix    = $request->suffix;
		$settings->default_weight_unit     = $request->weight_unit;
		$settings->default_length_unit     = $request->length_unit;
		$settings->default_volume_unit     = $request->volume_unit;
   		$settings->save();

   		return response()->json([], 200);
   	}



   	/**
   	 * Get currency locale
   	 * @param  [type] $currency [description]
   	 * @return [type]           [description]
   	 */
   	private function currencyLocale($currency)
   	{
   		return 'en-US';
   	}
}
