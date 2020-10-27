<?php

namespace App\Http\Controllers\Main\Auth\Password;

use App\Model\User;
use App\Model\SettingsSeo;
use App\Model\PasswordReset;
use Illuminate\Http\Request;
use App\Model\SettingsGeneral;
use App\Model\SettingsSecurity;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Auth\JobPasswordReset;
use App\Notifications\Auth\Password\PasswordReset as PasswordNotification;

class ResetController extends Controller
{
    function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function settings()
    {
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
    public function reset(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email|max:60'
        ]);

        // Get user with that email
        $user = User::whereEmail($request->email)->active()->first();

        // Check if user exists
        if ($user) {
            
            // Generate token
            $token    = strtoupper(bin2hex(openssl_random_pseudo_bytes(64)));

            // Save token
            $password        = new PasswordReset;
            $password->email = $request->email;
            $password->token = $token;
            $password->save();

            // Send notification
            JobPasswordReset::dispatch($user, $token);

            return response([]);

        }else{
            // User not found, but sent 200 response
            return response([]);
        }
        
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
}
