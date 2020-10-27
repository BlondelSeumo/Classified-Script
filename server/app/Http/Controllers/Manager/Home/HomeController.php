<?php

namespace App\Http\Controllers\Manager\Home;


use App\Model\Offer;
use App\Model\Store;
use App\Model\Message;
use App\Model\Classified;
use App\Model\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }


    /**
     * Get home stats
     *
     * @param Request $request
     * @return void
     */
    public function home(Request $request)
    {
        // Get user id
        $id              = auth()->id();

        // New deals query
        $deals           = new Classified();

        // Count total deals
        $deals_count     = $deals->whereUserId($id)->count();

        // Get user deals
        $recent          = $deals->whereUserId($id)->withCount('statistics')
                                                   ->orderBy('statistics_count', 'desc')
                                                   ->take(10)
                                                   ->get();

        // Count total visits
        $visits_count    = auth()->user()->statistics->count();

        // Count total messages
        $messages_count  = Message::whereUserTo($id)->count();

        // Count total offers 
        $offers_count    = Offer::whereToId($id)->count();

        // Count total shops
        $shops_count     = Store::whereUserId($id)->count();

        // Count total followers 
        $followers_count = auth()->user()->followers->count();

        // Return response
        return response([
            'shops_count'     => $shops_count,
            'visits_count'    => $visits_count,
            'offers_count'    => $offers_count,
            'recent'          => $recent,
            'messages_count'  => $messages_count,
            'followers_count' => $followers_count,
            'deals_count'     => $deals_count
        ]);
    }


    /**
     * Get dashboard required info
     *
     * @return void
     */
    public function info()
    {
        // Get user subscription
        $subscription = Subscription::whereUserId(auth()->id())->latest()->firstOrFail();

        // Count user shops
        $shops        = Store::whereUserId(auth()->id())->count();

        // Get today deals
        $deals        = Classified::whereUserId(auth()->id())->where('created_at', '>=', now()->subDay())->count();

        // Return shops left for this user
        return response([
            'storesLeft' => $subscription->plan->stores_limit - $shops,
            'dealsLeft'  => $subscription->plan->classifieds_limit - $deals
        ]);
    }
}
