<?php

namespace App\Http\Controllers\Main\Shop;

use App\Http\Controllers\Controller;
use App\Model\Follower;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use App\Model\Store;
use Illuminate\Http\Request;

class ShopController extends Controller
{

	/**
	 * Get shop
	 * @param  Request $request [description]
	 * @param  [type]  $name    [description]
	 * @return [type]           [description]
	 */
    public function shop(Request $request, $name)
    {
    	// Get shop
        $shop         = Store::with([
                                'country' => function($query) {
                                    $query->select('id', 'name');
                                },
                                'state'   => function($query) {
                                    $query->select('id', 'name');
                                },
                                'city'    => function($query) {
                                    $query->select('id', 'name');
                                }
                             ])
                             ->whereStore($name)
                             ->where('isActive', true)
                             ->where('isPending', false)
                             ->where('isExpired', false)
                             ->firstOrFail();
         
        // Check if user follow this store
        $isFollowing = $this->isFollowing($shop->id);

        // Return response
    	return response([
            'shop'        => $shop,
            'isFollowing' => $isFollowing,
            'seo'         => $this->seo($shop)
        ]);
    }


    /**
     * Check if user follow this shop
     * @param  [type]  $shopId [description]
     * @return boolean         [description]
     */
    protected function isFollowing($shopId)
    {
        // Get user id
        $id       = auth()->id();
        
        // Check if already following
        $follower = Follower::whereUserId($id)->whereStoreId($shopId)->first();

        return $follower ? true : false;
    }


    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo($shop)
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
        if ($shop->description) {
            $description = substr(clean($shop->description), 0, 150);
        }else{
            $description = $shop->title;
        }

        // Get shop image
        if ($shop->cover) {
            $image = index("storage/app/$shop->cover");
        }else{
            $image = index("storage/app/uploads/default/store/no-cover.png");
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
