<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\Model\Follower;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class FollowingController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Get shops that user follows
     * @return [type] [description]
     */
    public function following()
    {
    	// Get shops
    	$shops = Follower::whereUserId(auth()->id())
    					 ->with('shop')
    					 ->select('id', 'store_id', 'followed_at', 'isEmailNotifications')
    					 ->latest()
    					 ->paginate(100);

    	return response([
            'shops' => $shops,
            'seo'   => $this->seo()
        ]);
    }



    /**
     * Unfollow shops
     * @param  Request  $request   [description]
     * @param  Follower $following [description]
     * @return [type]              [description]
     */
    public function unfollow(Request $request, Follower $following)
    {
    	// Loop trough articles
        foreach ($request->shops as $follow) {
            $following->whereStoreId($follow['store_id'])->whereUserId(auth()->id())->delete();
        }

        // Success
        return response([]);
    }



    /**
     * Enable email notifications
     * @param  Request  $request   [description]
     * @param  Follower $following [description]
     * @return [type]              [description]
     */
    public function enable(Request $request, Follower $following)
    {
    	// Loop trough articles
        foreach ($request->shops as $follow) {
            $following->whereStoreId($follow['store_id'])->whereUserId(auth()->id())->update([
            	'isEmailNotifications' => true
            ]);
        }

        // Success
        return response([]);
    }



    /**
     * Disable email notifications
     * @param  Request  $request   [description]
     * @param  Follower $following [description]
     * @return [type]              [description]
     */
    public function disable(Request $request, Follower $following)
    {
    	// Loop trough articles
        foreach ($request->shops as $follow) {
            $following->whereStoreId($follow['store_id'])->whereUserId(auth()->id())->update([
            	'isEmailNotifications' => false
            ]);
        }

        // Success
        return response([]);
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
