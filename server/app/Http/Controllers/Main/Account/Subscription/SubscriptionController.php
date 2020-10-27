<?php

namespace App\Http\Controllers\Main\Account\Subscription;

use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use App\Model\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



   	/**
   	 * Get user subscriptions
   	 * @param  Request $request [description]
   	 * @return [type]           [description]
   	 */
    public function subscription(Request $request)
    {
    	// Get subscriptions
    	$subscriptions = Subscription::whereUserId(auth()->id())->with('plan')->latest()->paginate(50);

    	return response([
        'subscriptions' => $subscriptions,
        'seo'           => $this->seo()
      ]);
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
