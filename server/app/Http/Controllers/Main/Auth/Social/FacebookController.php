<?php

namespace App\Http\Controllers\Main\Auth\Social;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class FacebookController extends Controller
{
    function __construct()
    {
    	$this->middleware('guest');
    }


    public function login(Request $request)
    {
		$user     = Socialite::driver('facebook')->stateless()->userFromToken($request->token);
		
		// Create user or get existing user
		$authUser = $this->findOrCreateUser($user);

		if ($authUser['failed']) {
			return response('failed', 401);
		}
		
		// Get token
		$token    = JWTAuth::fromUser($authUser);
        
        // Return token
        return response($token);
    }


    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($facebookUser)
    {
        $authUser = User::whereProviderName('facebook')->whereProviderId($facebookUser->id)->first();

        // Exists, return it
        if ($authUser){
            return $authUser;
        }

        // New, create one
        return User::create([
			'username'      => strtolower(str_replace(' ', '_', $facebookUser->name)),
			'email'         => $facebookUser->email,
			'provider_name' => 'facebook',
			'provider_id'   => $facebookUser->id,
			'avatar'        => $this->avatar($facebookUser->avatar),
			
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
