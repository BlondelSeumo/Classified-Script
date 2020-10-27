<?php

namespace App\Http\Controllers\Api\Main\Account\Wishlist;

use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Watchlist;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Add product to wishlist
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
    		'product' => 'required|exists:products,unique_id'
    	]);

    	// Get product
    	$product = Product::whereUniqueId($request->product)->first();

    	// Add to wishlist
    	$wishlist = Watchlist::create([
    		'product_id' => $product->id,
    		'user_id'    => auth()->id()
    	]);

    	// Success
    	return response()->json($wishlist, 200);
    }
}
