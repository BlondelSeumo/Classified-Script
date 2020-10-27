<?php

namespace App\Http\Controllers\Main\Auth\Password;

use Carbon\Carbon;
use App\Model\User;
use App\Model\SettingsSeo;
use App\Model\SettingsAuth;
use App\Model\PasswordReset;
use Illuminate\Http\Request;
use App\Model\SettingsGeneral;
use App\Model\SettingsSecurity;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get settings
     *
     * @return void
     */
    public function settings(Request $request)
    {
        // Get token expiry time
        $expiry = SettingsAuth::whereId(1)->first();

        // Check token
        $token     = PasswordReset::whereToken($request->token)->firstOrFail();

        // Expired token
        if ($this->isExpired($token, $expiry->activation_expires_after)) {
            return response([], 404);
        }

        // Get logo
        $logo      = SettingsGeneral::select('whiteLogo as logo')->first();
        
        // Get recaptcha settings
        $recaptcha = SettingsSecurity::select('isRecaptcha as recaptcha')->first();

        // Return response
        return response([
            'logo'      => $logo,
            'recaptcha' => $recaptcha,
            'siteKey'   => config('captcha.siteKey'),
            'seo'       => $this->seo()
        ]);
    }


    /**
     * Reset password
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        // Get token expiry time
        $expiry = SettingsAuth::whereId(1)->first();

        // Check token
        $token     = PasswordReset::whereToken($request->token)->firstOrFail();

        // Expired token
        if ($this->isExpired($token, $expiry->activation_expires_after)) {
            return response([], 404);
        }
        
        // Validate request
        $request->validate([
            'email'    => 'required|email|max:60',
            'password' => 'required|confirmed|max:60',
        ]);

        // Get user with that email
        $user = User::whereEmail($request->email)->active()->firstOrFail();

        // Update password
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete token
        $token->delete();

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
        
        // Generate tagline
        $tagline      = $general->tagline;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description = $seo->description;

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'tagline'     => $tagline,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
        ];
    }


    /**
     * Check if token expired
     *
     * @param PasswordReset $token
     * @param [type] $expiry
     * @return boolean
     */
    private function isExpired(PasswordReset $token, $expiry)
    {
        // Check if password link expired
        $created = new Carbon($token->created_at);
        $now     = now();
        $diff    = $created->diffInMinutes($now);

        // Check if expired
        return $diff > $expiry ? true : false;
    }
}
