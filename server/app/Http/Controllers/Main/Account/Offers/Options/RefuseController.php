<?php

namespace App\Http\Controllers\Main\Account\Offers\Options;

use App\Model\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Offers\JobOfferRefused;

class RefuseController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * refuse selected offers
     * @param  Request $request  [description]
     * @param  Offer $offers [description]
     * @return [type]            [description]
     */
    public function refuse(Request $request, Offer $offers)
    {
    	// Loop trough offers
        foreach ($request->offers as $offer) {

            $exists = $offers->whereUniqueId($offer['unique_id'])->first();

            if ($exists) {
                
                $exists->update([
                    'isAccepted' => false,
                    'isRefused'  => true,
                    'isPending'  => false,
                ]);

                // Send notification
                JobOfferRefused::dispatch($exists);
            }
        }

        // Success
        return response([]);
    }
}
