<?php

namespace App\Http\Controllers\Main\Auth;

use App\Http\Controllers\Controller;
use App\Model\SettingsAuth;
use App\Model\SettingsGeneral;
use App\Model\SettingsSecurity;
use App\Model\SettingsSeo;
use App\Model\SettingsSocial;
use App\Model\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Packages\Authenticate\Login;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Login
{
    /**
     * This trait has all the login throttling functionality.
     */
    use ThrottlesLogins;



    /**
     * Max login attempts allowed.
     */
    public $maxAttempts;



    /**
     * Number of minutes to lock the login.
     */
    public $decayMinutes;



    function __construct()
    {
        $this->middleware('guest')->except('logout');
        
        $settings           = SettingsAuth:: find(1);
        $this->maxAttempts  = $settings->maxAttempts;
        $this->decayMinutes = $settings->retries_in;
    }

    /**
     * Get login settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get login settings
        $settings  = SettingsAuth::select('isRegister as register', 'loginBy as login_type')->first();
        
        // Get social media settings
        $social    = SettingsSocial::find(1);
        
        // Get logo
        $logo      = SettingsGeneral::select('whiteLogo as logo')->first();
        
        // Get recaptcha settings
        $recaptcha = SettingsSecurity::select('isRecaptcha as recaptcha')->first();

        // Return response
        return response([
            'login'     => $settings,
            'social'    => $social,
            'logo'      => $logo,
            'recaptcha' => $recaptcha,
            'siteKey'   => config('captcha.siteKey'),
            'seo'       => $this->seo()
        ]);

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //check if the user has too many login attempts.
        if ($this->hasTooManyLoginAttempts($request)){
            //Fire the lockout event.
            $this->fireLockoutEvent($request);
            //redirect the user back after lockout.
            return $this->sendLockoutResponse($request);
        }

        // Get auth settings
        $authentication = SettingsAuth::find(1);
        $security       = SettingsSecurity::find(1);

        // Check captcha, if required
        $captcha        = $security->isRecaptcha ? 'required|captcha' : 'nullable';

        // Make validation
        $request->validate([
            'credentials'  => 'required|max:100',
            'password'     => 'required',
            'captchaToken' => $captcha
        ]);

        // Check login type
        switch ($authentication->loginBy) {
            case 'email':
                return $this->loginByEmail($request);
                break;

            case 'username':
                return $this->loginByUsername($request);
                break;

            case 'phone':
                return $this->loginByPhone($request);
                break;

            case 'ue':
                return $this->loginByUsernameEmail($request);
                break;

            case 'uep':
                return $this->loginByUsernameEmailPhone($request);
                break;
            
            default:
                return $this->loginByEmail($request);
                break;
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



    /**
     * Username used in ThrottlesLogins trait
     * 
     * @return string
     */
    public function username(){
        return 'credentials';
    }
}
