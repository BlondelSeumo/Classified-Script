<?php

namespace App\Http\Controllers\Main\Account\Offers\Options;

use App\Model\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Offers\JobOfferAccepted;

class AcceptController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * accept selected offers
     * @param  Request $request  [description]
     * @param  Offer $offers [description]
     * @return [type]            [description]
     */
    public function accept(Request $request, Offer $offers)
    {
    	// Loop trough offers
        foreach ($request->offers as $offer) {
            $exists = $offers->whereUniqueId($offer['unique_id'])->first();

            if ($exists) {

                $exists->update([
                    'isAccepted' => true,
                    'isRefused'  => false,
                    'isPending'  => false,
                ]);

                // Send notification
                JobOfferAccepted::dispatch($exists);
            }
        }

        // Success
        return response([]);
    }
}
