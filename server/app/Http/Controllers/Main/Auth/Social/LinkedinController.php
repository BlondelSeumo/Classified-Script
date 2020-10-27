<?php

namespace App\Http\Controllers\Main\Auth\Social;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class LinkedinController extends Controller
{
    function __construct()
    {
    	$this->middleware('guest');
    }


    public function login()
    {
    	// Socialite will pick response data automatic 
		$user     = Socialite::with('linkedin')->stateless()->user();
		
		// Create user or get existing user
		//$authUser = $this->findOrCreateUser($user);
		
		// Get token
		//$token    = JWTAuth::fromUser($authUser);
        
        // Return token
        return response()->json($user);
    }


    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $instagramUser
     * @return User
     */
    private function findOrCreateUser($instagramUser)
    {
        $authUser = User::whereProviderName('linkedin')->whereProviderId($instagramUser->id)->first();

        // Exists, return it
        if ($authUser){
            return $authUser;
        }

        // New, create one
        return User::create([
			'username'      => $instagramUser->nickname,
			'email'         => $instagramUser->email,
			'provider_name' => 'linkedin',
			'provider_id'   => $instagramUser->id,
			'avatar'        => $this->avatar($instagramUser->avatar),
			
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
