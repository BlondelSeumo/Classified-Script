<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\SettingsSocial;
use ConfigWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SocialController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-social');
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get settings
        $settings = SettingsSocial::find(1);

    	return response()->json([
    		'settings' => $settings,
    		'config'   => config('services')
    	], 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        // Make validation
        $request->validate([
            'enable_facebook'   => 'required|boolean',
            'enable_twitter'    => 'required|boolean',
            'enable_google'     => 'required|boolean',
            'enable_instagram'  => 'required|boolean',
            'enable_pinterest'  => 'required|boolean',
            'enable_linkedin'   => 'required|boolean',
            'enable_vk'         => 'required|boolean'
        ]);

        // Update settings in database
        $settings = SettingsSocial::where('id', 1)->update([
            'isFacebook'   => $request->enable_facebook,
            'isTwitter'    => $request->enable_twitter,
            'isGoogle'     => $request->enable_google,
            'isInstagram'  => $request->enable_instagram,
            'isPinterest'  => $request->enable_pinterest,
            'isLinkedin'   => $request->enable_linkedin,
            'isVk'         => $request->enable_vk
        ]);

        // Write settings in service file
        $config = new ConfigWriter('services');

        $config->set('facebook.client_id', $request->facebook_key);
        $config->set('facebook.client_secret', $request->facebook_secret);
        $config->set('facebook.redirect', $this->callback('facebook'));

        $config->set('twitter.client_id', $request->twitter_key);
        $config->set('twitter.client_secret', $request->twitter_secret);
        $config->set('twitter.redirect', $this->callback('twitter'));

        $config->set('google.client_id', $request->google_key);
        $config->set('google.client_secret', $request->google_secret);
        $config->set('google.redirect', $this->callback('google'));

        $config->set('instagram.client_id', $request->instagram_key);
        $config->set('instagram.client_secret', $request->instagram_secret);
        $config->set('instagram.redirect', $this->callback('instagram'));

        $config->set('pinterest.client_id', $request->pinterest_key);
        $config->set('pinterest.client_secret', $request->pinterest_secret);
        $config->set('pinterest.redirect', $this->callback('pinterest'));

        $config->set('linkedin.client_id', $request->linkedin_key);
        $config->set('linkedin.client_secret', $request->linkedin_secret);
        $config->set('linkedin.redirect', $this->callback('linkedin'));

        $config->set('vkontakte.client_id', $request->vk_key);
        $config->set('vkontakte.client_secret', $request->vk_secret);
        $config->set('vkontakte.redirect', $this->callback('vk'));

        $config->save();

        // clear cache
        Artisan::call('config:clear');

        // Success
        return response()->json([], 200);
    }


    /**
     * Generate callback url
     * based on given provider
     * @param  [type]   $provider [description]
     * @return function           [description]
     */
    public function callback($provider)
    {
        return index('auth/' . $provider . '/callback');
    }
}
