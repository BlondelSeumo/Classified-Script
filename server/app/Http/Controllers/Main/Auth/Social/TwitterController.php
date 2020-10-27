<?php

namespace App\Http\Controllers\Main\Auth\Social;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Image;
use Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class TwitterController extends Controller
{
    function __construct()
    {
    	$this->middleware('guest');
    }



    /**
     * Get redirect link for Twitter
     * @return [type] [description]
     */
    public function redirect()
    {
        return response(Socialite::driver('twitter')->redirect()->getTargetUrl());
    }



    /**
     * Login via twitter
     * @return [type] [description]
     */
    public function login(Request $request)
    {
    	// Socialite will pick response data automatic 
    	$user     = Socialite::driver('twitter')->user();
		
		// Create user or get existing user
		$authUser = $this->findOrCreateUser($user);

		if ($authUser['failed']) {
			return response('failed');
		}
		
		// Get token
		$token    = JWTAuth::fromUser($authUser);
        
        // Return token
        return response($user);
    }


    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $twitterUser
     * @return User
     */
    private function findOrCreateUser($twitterUser)
    {
        $authUser = User::whereProviderName('facebook')->whereProviderId($twitterUser->id)->first();

        // Exists, return it
        if ($authUser){
            return $authUser;
        }

        // New, create one
        return User::create([
			'username'      => strtolower(str_replace(' ', '_', $twitterUser->name)),
			'email'         => $twitterUser->email,
			'provider_name' => 'facebook',
			'provider_id'   => $twitterUser->id,
			'avatar'        => $this->avatar($twitterUser->avatar),
			
        ]);
    }


    /**
     * Upload user avatar
     * @param  [type] $avatar [description]
     * @return [type]         [description]
     */
    private function avatar($avatar)
    {
    	try {

    		// Generate file name
			$filename = uniqueId(64) . '.png';
			
			// Save image
			$image    = Image::make($avatar)->save(storage_path('app/uploads/avatars/' . $filename));
			
			// Get url
			return 'uploads/avatars/' . $filename;

    	} catch (\Exception $e) {
    		return null;
    	}
    }
}
