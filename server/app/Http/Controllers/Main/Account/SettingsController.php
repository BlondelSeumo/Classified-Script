<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Khsing\World\Models\Country;

class SettingsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Fetch account required data
     * @return [type] [description]
     */
    public function fetch()
    {
        // Get countries
        $countries = Country::all();

        return response([
            'countries' => $countries,
            'seo'       => $this->seo()
        ]);
    }


    /**
     * Update profile
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Get user id
    	$uid            = auth()->id();

		// Make validation
		$request->validate([
            'username'         => 'required|max:32|unique:users,username,' . $uid,
            'email'            => 'required|max:60|email|unique:users,email,' . $uid,
            'phone'            => 'nullable|max:60|unique:users,phone,' . $uid,
            'current_password' => 'required_with:new_password|max:60',
            'new_password'     => 'required_with:current_password|max:60',
            'country'          => 'nullable|integer|exists:world_countries,id',
            'state'            => 'nullable|integer|exists:world_divisions,id',
            'city'             => 'nullable|integer|exists:world_cities,id',
            'language'         => 'max:2'
		]);

		// Update user
        $user           = User::find($uid);
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->country  = $request->country;
        $user->state    = $request->state;
        $user->city     = $request->city;

        // Check if password change required
        if ($request->new_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                
                // Password not matched
                return response('error_password', 422);

            }else{

                // Password matched, change it
                $user->password = bcrypt($request->new_password);

            }
        }

        // Check if want to change avatar
        if ($request->hasFile('avatar')) {
            $user->uploadImage($request->file('avatar'), 'avatar');
        }

        // Response
        $response = $user;

        // Save user
        $user->save();

        // Success
        return response($response);
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
