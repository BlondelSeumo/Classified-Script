<?php

namespace App\Http\Controllers\Main\Account\Deals\Promote;

use Stripe\Charge;
use Stripe\Stripe;
use App\Model\User;
use App\Model\Classified;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;
use App\Model\SettingsGeneral;
use App\Model\ClassifiedPackage;
use App\Model\ClassifiedPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\Administrator\Deals\JobDealPromoted;

class PromoteController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Promote a deal
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function promote(Request $request)
    {
    	// Check deal
		$deal     = Classified::with('user')
							  ->whereUniqueId($request->id)
							  ->where('isPending', false)
							  ->whereUserId(auth()->id())
							  ->firstOrFail();
		
		// Get deals packages
		$packages = ClassifiedPackage::orderBy('type', 'asc')->latest()->get();

		// Check if user select a package while creating ad
		if ($request->package) {
			// Get package
			$package = ClassifiedPackage::whereSlug($request->package)->first();

			if ($package) {
				$selected = $package;
			}else{
				$selected = null;
			}
		}else{
			$selected = null;
		}

		return response([
			'deal'     => $deal,
			'packages' => $packages,
			'selected' => $selected,
			'seo'      => $this->seo(),
		]);
    }



    /**
     * Deal promotion Checkout
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checkout(Request $request)
    {
    	// Check deal
		$deal     = Classified::whereUniqueId($request->id)
							  ->where('isPending', false)
							  ->whereUserId(auth()->id())
							  ->firstOrFail();

		// Check package
		$package  = ClassifiedPackage::whereId($request->package)->firstOrFail();

		// Set Strip secret key
    	Stripe::setApiKey("sk_test_LRFbNEFtDhXVrXgFJlByHw7U");

    	// Charge user
		$charge   = Charge::create([
			'amount'      => $package->price * 1000,
			'currency'    => $package->currency,
			'description' => $package->title,
			'source'      => $request->token,
		]);

		// Check if payment succeeded
		if ( $charge['paid'] && ($charge['status'] == 'succeeded') ) {

			// Payment success promote deal
			$subscription = ClassifiedPayment::create([
				'unique_id'      => uniqueId(),
				'user_id'        => auth()->id(),
				'transaction_id' => $charge['id'],
				'amount'         => $charge['amount'] / 100,
				'currency'       => strtoupper($charge['currency']),
				'locale'         => $package->locale,
				'package_id'     => $package->id,
				'deal_id'        => $deal->id,
				'isSucceed'      => true,
				'isPending'      => true
			]);

			// Send notification to admins
			JobDealPromoted::dispatch($deal, $subscription);

			// Paid
			return response(['status' => 'paid']);

		}else{

			// Not paid
			return response(['status' => 'failed']);

		}
    }


    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo()
    {
        // Get settings general
        $general      = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo          = SettingsSeo::find(1);
        
        // Generate title
        $title        = $general->title;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description  = $seo->description;

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon
        ];
    }
}
