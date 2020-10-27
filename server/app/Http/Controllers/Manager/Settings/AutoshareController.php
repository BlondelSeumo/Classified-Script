<?php

namespace App\Http\Controllers\Manager\Settings;

use App\Http\Controllers\Controller;
use App\Model\ManagerSettingsAutoshare;
use Illuminate\Http\Request;

class AutoshareController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }



    /**
     * Get autoshare settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get settings
    	$autoshare = ManagerSettingsAutoshare::firstOrCreate(
    		['user_id' => auth()->id()],
    		[
				'facebook'   => null,
				'twitter'    => null,
				'telegram'   => null,
				'isFacebook' => false,
				'isTwitter'  => false,
				'isTelegram' => false
    		]
    	);

    	return response($autoshare);
    }


	/**
	 * Update autoshare settings
	 *
	 * @param Request $request
	 * @return void
	 */
    public function update(Request $request)
    {
    	// Get settings
    	$autoshare = ManagerSettingsAutoshare::whereUserId(auth()->id())->firstOrFail();

    	// Validate request
    	$request->validate([
			'enable_facebook'             => 'required|boolean',
			'enable_twitter'              => 'required|boolean',
			'enable_telegram'             => 'required|boolean',
			'facebook_app_id'             => 'required_if:enable_facebook,1|max:100',
			'facebook_app_secret'         => 'required_if:enable_facebook,1|max:100',
			'facebook_page_access_token'  => 'required_if:enable_facebook,1|max:500',
			'twitter_consumer_key'        => 'required_if:enable_twitter,1|max:100',
			'twitter_consumer_secret'     => 'required_if:enable_twitter,1|max:100',
			'twitter_access_token'        => 'required_if:enable_twitter,1|max:100',
			'twitter_access_token_secret' => 'required_if:enable_twitter,1|max:100',
			'telegram_api_token'          => 'required_if:enable_telegram,1|max:100',
			'telegram_bot_username'       => 'required_if:enable_telegram,1|max:100',
			'telegram_channel_username'   => 'required_if:enable_telegram,1|max:100',
			'telegram_channel_signature'  => 'required_if:enable_telegram,1|max:500'
    	]);

    	// Update settings
    	$autoshare->update([
    		'facebook'   => json_encode([
				'app_id'            => $request->facebook_app_id,
				'app_secret'        => $request->facebook_app_secret,
				'page_access_token' => $request->facebook_page_access_token
    		], 200),
    		'isFacebook' => $request->enable_facebook,

    		'twitter'    => json_encode([
				'consumer_key'        => $request->twitter_consumer_key,
				'consumer_secret'     => $request->twitter_consumer_secret,
				'access_token'        => $request->twitter_access_token,
				'access_token_secret' => $request->twitter_access_token_secret
    		], 200),
    		'isTwitter'  => $request->enable_twitter,
    		
    		'telegram'   => json_encode([
				'api_token'         => $request->telegram_api_token,
				'bot_username'      => $request->telegram_bot_username,
				'channel_username'  => $request->telegram_channel_username,
				'channel_signature' => $request->telegram_channel_signature
    		], 200),
    		'isTelegram'  => $request->enable_telegram,
    	]);

    	// Success
    	return response([]);
    }
}
