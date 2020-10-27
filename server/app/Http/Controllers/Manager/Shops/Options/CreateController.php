<?php

namespace App\Http\Controllers\Manager\Shops\Options;

use App\Model\User;
use App\Model\Store;
use App\Model\Subscription;
use Illuminate\Http\Request;
use App\Model\SettingsSecurity;
use Khsing\World\Models\Country;
use App\Http\Controllers\Controller;
use App\Notifications\Manager\Shops\ShopCreated;
use App\Jobs\Administrator\Pending\JobPendingShop;

class CreateController extends Controller
{
    
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }


    /**
     * Get countries and users
     * @return [type] [description]
     */
    public function fetch()
    {
		// Check if user exceeded shop limit
		$exceeded = $this->exceeded() ? true : false;

		// Get countries
		$countries = Country::oldest('name')->get();

    	return response([
			'countries' => $countries,
			'exceeded'  => $exceeded
		]);
    }



    /**
     * Create new shop
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
			'title'          => 'required|max:100|unique:stores,title',
			'subtitle'       => 'nullable|max:100',
			'description'    => 'nullable',
			'store'          => 'required|alpha_dash|max:100|unique:stores,store',
			'address1'       => 'nullable|max:100',
			'address2'       => 'nullable|max:100',
			'zip'            => 'nullable|max:30',
			'country'        => 'nullable|integer|exists:world_countries,id',
			'state'          => 'nullable|integer|exists:world_divisions,id',
			'city'           => 'nullable|integer|exists:world_cities,id',
			'customer_email' => 'nullable|max:60|email',
			'phone'          => 'nullable|max:60',
			'primary_locale' => 'required|in:en,fr,ar',
			'logo'           => 'image|mimes:png,jpg,jpeg,svg|max:5000',
			'cover'          => 'image|mimes:png,jpg,jpeg,svg|max:5000'
		]);
		
		// Get deal status
        $status = $this->status();

    	// Save store
    	$shop   = Store::create([
			'unique_id'      => uniqueId(),
			'user_id'        => auth()->id(),
			'title'          => $request->title,
			'subtitle'       => $request->subtitle,
			'description'    => $request->description,
			'store'          => $request->store,
			'address1'       => $request->address1,
			'address2'       => $request->address2,
			'zip'            => $request->zip,
			'country'        => $request->country,
			'state'          => $request->state,
			'city'           => $request->city,
			'customer_email' => $request->customer_email,
			'phone'          => $request->phone,
			'primary_locale' => $request->primary_locale,
            'isPending'    => $status['isPending'],
            'isActive'     => $status['isActive']
    	]);

		// Send action to admins
		if ($status['isPending']) {
			JobPendingShop::dispatch($shop);
		}

		// Send notification to user
		$shop->owner->notify(new ShopCreated($shop));

    	// Success
    	return response([
			'exceeded' => $this->exceeded(),
			'left'     => $this->left()
		]);
    }
	

	/**
	 * Check if user exceeded shop limits
	 *
	 * @return void
	 */
	private function exceeded()
	{
		// Get user total shops
		$shops        = auth()->user()->shops->count();

		// Get allowed shops
		$subscription = Subscription::whereUserId(auth()->id())->where('isExpired', false)->firstOrFail();

		// Check if user exceeded shop limit
		if ($shops >= $subscription->plan->stores_limit) {
			return true;
		}

		// No limits exceeded yet
		return false;
	}
	

	/**
	 * Check shops left
	 *
	 * @return void
	 */
	private function left()
	{
		// Get user total shops
		$shops        = auth()->user()->shops->count();

		// Get allowed shops
		$subscription = Subscription::whereUserId(auth()->id())->where('isExpired', false)->firstOrFail();

		if ($subscription->plan->stores_limit == $shops) {
			return 0;
		}

		return $subscription->plan->stores_limit - $shops;
	}

	/**
     * Check if auto approve enabled
     * @return [type] [description]
     */
    private function status()
    {
        // Get current online user
        $user    = auth()->user();

        // If admin, owner or moderator, not need for approval
        if ($user->isOwner() || $user->isAdministrator() || $user->isModerator()) {
           return array('isPending' => false, 'isActive' => true);
        }

        // Else get default setting
        $settings = SettingsSecurity::find(1);

        // Check auto approve settings
        return $settings->autoApproveStores ? array('isPending' => false, 'isActive' => true) : array('isPending' => true, 'isActive' => false);
    }
}
