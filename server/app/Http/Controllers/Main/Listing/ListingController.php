<?php

namespace App\Http\Controllers\Main\Listing;

use App\Http\Controllers\Controller;
use App\Jobs\Main\Listing\Tracking;
use App\Model\Advertisement;
use App\Model\Classified;
use App\Model\ClassifiedStatistics;
use App\Model\FieldClassified;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;
use Packages\Tracker\Tracker;
use App\Model\Currency;

class ListingController extends Controller
{

	/**
	 * Get listing
	 * @param  Request $request [description]
	 * @param  [type]  $slug    [description]
	 * @return [type]           [description]
	 */
    public function listing(Request $request, $slug)
    {
    	// Check if admin online
        if (auth()->check() && ( auth()->user()->isOwner() || auth()->user()->isAdministrator() || auth()->user()->isModerator() )) {
            
            // Get deal even if it's not active
            $listing = Classified::with('category', 'user', 'country', 'state', 'city')
                             ->withCount(['statistics', 'comments' => function($query) {
                                $query->where('isDeal', true);
                             }])
                             ->whereUniqueSlug($slug)
                             ->firstOrFail();

        }else{

            // Deal must be active
            $listing = Classified::with('category', 'user', 'country', 'state', 'city')
                             ->withCount(['statistics', 'comments' => function($query) {
                                $query->where('isDeal', true);
                             }])
                             ->whereUniqueSlug($slug)
                             ->active()
                             ->firstOrFail();

        }

        // Get fields
        $fields  = FieldClassified::with('field')->whereDealId($listing->id)->get();

        // Track this visit
        $this->track($request, $listing);

        // Get related deals
        $related        = $this->related($listing);
        
        // Get advertisements
        $advertisements = Advertisement::find(1);

        // Generate seo
        $seo            = $this->seo($listing);

        // Get currencies
        $currencies     = Currency::latest('name')->get();

    	// Show listing
    	return response()->json([
            'listing'        => $listing,
            'fields'         => $fields,
            'currencies'     => $currencies,
            'related'        => $related,
            'advertisements' => $advertisements,
            'seo'            => $seo,
		], 200);
    }


    /**
     * Track this visit and save information
     * You can use this info to improve the service
     * @param  Request    $request [description]
     * @param  Classified $deal    [description]
     * @return [type]              [description]
     */
    protected function track(Request $request, Classified $deal)
    {
        // Get tracker
        $tracker  = new Tracker;

        // Get user ip
        $ip       = ip();
        
        // Check if tracking succeed
        if (!$tracker->isSucceed) {
            return;
        }
        
        // New stats query
        $tracking = new ClassifiedStatistics;
        
        // Check if already visited
        $visit    = $tracking->whereDealId($deal->id)->whereIp($ip)->first();

        // Already visited? update last visit
        if ($visit) {

            $visit->last_visit = now();
            $visit->save();

            // Success
            return;
        }

        // New visit
        $tracking->create([
            'deal_id'             => $deal->id,
            'ip'                  => $ip,
            'provider'            => $tracker->provider,
            'countryCode'         => $tracker->countryCode,
            'countryName'         => $tracker->countryName,
            'state'               => $tracker->state,
            'city'                => $tracker->city,
            'continent'           => $tracker->continent,
            'longitutde'          => $tracker->longitutde,
            'latitude'            => $tracker->latitude,
            'browserName'         => $tracker->browserName,
            'browserVersion'      => str_replace('_', '.', $tracker->browserVersion),
            'browserLanguage'     => $request->language,
            'platformName'        => $tracker->platformName,
            'platformVersion'     => str_replace('_', '.', $tracker->platformVersion),
            'isPhone'             => $tracker->isPhone,
            'isTablet'            => $tracker->isTablet,
            'isDesktop'           => $tracker->isDesktop,
            'isRobot'             => $tracker->isRobot,
            'robotName'           => $tracker->robotName,
            'deviceName'          => $tracker->deviceName,
            'screenWidth'         => $request->width,
            'screenHeight'        => $request->height,
            'referrer'            => $tracker->referrer,
            'referrerDomain'      => $tracker->referrerDomain,
            'searchEngineKeyword' => $tracker->searchEngineKeyword,
            'first_visit'         => $tracker->first_visit,
            'last_visit'          => $tracker->last_visit
        ]);

        // Success
        return;

    }



    /**
     * Get related deals
     * @param  Classified $listing [description]
     * @return [type]              [description]
     */
    protected function related(Classified $listing)
    {
        // Get related deals
        $deals = Classified::with('user')
                           ->where('id', '!=', $listing->id)
                           ->where('title', 'LIKE', "%$listing->title%")
                           ->orWhere('description', 'LIKE', "%$listing->description%")
                           ->where('id', '!=', $listing->id)
                           ->active()
                           ->inRandomOrder()
                           ->take(6)
                           ->get();

        // Return related deals
        return $deals;
    }



    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo($listing)
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
        if ($listing->description) {
            $description = substr($listing->description, 0, 150);
        }else{
            $description = $listing->title;
        }

        // Get main image
        if ($listing->photosNumber === 0) {
            $image = index('storage/app/uploads/default/classifieds/no-image.png');
        }else{
            $image = index("storage/app/uploads/classifieds/$listing->unique_id/preview_0.jpg");
        }

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
            'image'       => $image,
        ];
    }
}
