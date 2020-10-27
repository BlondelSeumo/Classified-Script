<?php

namespace App\Http\Controllers\Main\Search;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Currency;
use App\Model\SettingsGeo;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

	/**
	 * Get search required data
	 * @return [type] [description]
	 */
    public function settings()
    {
    	// Get categories
    	$categories = Category::whereParentId(false)->latest('title')->get();

    	// Get currencies
    	$currencies = Currency::latest('name')->get();

    	// Get default currency
    	$settings   = SettingsGeo::find(1);
    	$currency   = Currency::whereId($settings->defaultCurrency)->select('code')->first();

    	// Return data
    	return response([
    		'categories' => $categories,
    		'currencies' => $currencies,
    		'currency'   => $currency
    	]);
    }
}
