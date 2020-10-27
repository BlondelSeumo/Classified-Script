<?php

namespace App\Http\Controllers\Main\Account\Offers\Options;

use App\Model\Offer;
use App\Model\Currency;
use App\Model\Classified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Offers\JobOfferReceived;

class SendController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function send(Request $request)
    {
        // Validate price
        $request->validate([
            'price'    => 'required|max:60',
            'currency' => 'required|exists:currencies,code'
        ]);

        // Check deal
        $deal  = Classified::whereUniqueId($request->id)->where('user_id', '!=', auth()->id())->active()->firstOrFail();

        // Send offer
        $offer = Offer::create([
            'unique_id' => uniqueId(),
            'deal_id'   => $deal->id,
            'from_id'   => auth()->id(),
            'to_id'     => $deal->user_id,
            'price'     => $request->price,
            'currency'  => $request->currency,
            'locale'    => $this->locale($request->currency)
        ]);

        // Send user notifications
        JobOfferReceived::dispatch($offer);

        // Success
        return response([]);
    }



    /**
     * Get currency locale
     *
     * @param [type] $code
     * @return void
     */
    private function locale($code)
    {
        // Get currency
        $currency = Currency::whereCode($code)->first();

        return $currency->locale;
    }
}
