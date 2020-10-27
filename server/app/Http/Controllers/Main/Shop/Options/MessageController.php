<?php

namespace App\Http\Controllers\Main\Shop\Options;

use App\Model\Store;
use App\Model\StoreMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Manager\Messages\NewMessage;

class MessageController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Send message to shop
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function message(Request $request)
    {
    	// Check if shop exists
    	$shop     = Store::where('store', $request->shop)
					     ->where('isActive', true)
					     ->where('isPending', false)
					     ->where('isExpired', false)
					     ->firstOrFail();

		// Make validation
		$request->validate([
			'subject' => 'required|max:100',
			'message' => 'required|max:750'
		]);

		// Save message
		$message = StoreMessage::firstOrCreate([
			'unique_id' => uniqueId(),
			'user_id'   => auth()->id(),
			'store_id'  => $shop->id,
			'subject'   => clean($request->subject),
			'message'   => clean($request->message)
		]);

		// Send notification
		$shop->owner->notify(new NewMessage($message, $shop));

		// Success
		return response([]);
    }
}
