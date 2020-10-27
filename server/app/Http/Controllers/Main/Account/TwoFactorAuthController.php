<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\Model\PasswordSecurity;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TwoFactorAuthController extends Controller
{
    function __construct()
	{
		$this->middleware('auth:api');
	}


	/**
	 * Get 2FA details
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function form(Request $request)
    {
    	// Get user
    	$user = auth()->user();

        $google2fa_url = "";

        if($user->passwordSecurity()->exists()){
            $google2fa     = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google2fa_url = $google2fa->getQRCodeInline(config('app.name'), $user->email, $user->passwordSecurity->google2fa_secret);
        }

        return response([
            'user'          => $user->with('passwordSecurity')->first(),
            'google2fa_url' => $google2fa_url,
            'seo'           => $this->seo()
        ]);
    }


    /**
     * Generate secret key
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function generate(Request $request)
    {
    	// Get user
		$user      = auth()->user();
		
		// Initialise the 2FA class
		$google2fa = app('pragmarx.google2fa');

	    // Add the secret key to the registration data
	    $password_security = PasswordSecurity::create([
			'user_id'          => $user->id,
			'google2fa_secret' => $google2fa->generateSecretKey()
	    ]);

	    // Generate QR Code
	    $google2fa->setAllowInsecureCallToGoogleApis(true);
        $google2fa_url = $google2fa->getQRCodeGoogleUrl(
            config('app.name'),
            $user->email,
            $user->passwordSecurity->google2fa_secret
        );

        // Set data
        $data = array(
			'password_security' => $password_security,
			'google2fa_url'     => $google2fa_url
        );

        // Success
	    return response()->json($data, 200);
    }


    /**
     * Enable 2FA
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function enable(Request $request)
    {
    	// Get user
        $user = auth()->user();

        // Validate request
        $request->validate([
        	'verifyCode' => 'required|max:10|min:6'
        ]);

		$google2fa = app('pragmarx.google2fa');
		$secret    = $request->verifyCode;
		$valid     = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $secret);

        // check if code correct
        if($valid){

        	// Enable 2FA
            $user->passwordSecurity->google2fa_enable = true;
            $user->passwordSecurity->save();
            return response()->json($user->passwordSecurity, 200);

        }else{

        	// Failed
            return response()->json([
            	'isFailed' => true
            ], 200);

        }
    }


    /**
     * Disable 2FA
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function disable(Request $request)
    {
    	// Get user
    	$user = auth()->user();

    	// Check password
    	if (!(Hash::check($request->password, $user->password))) {

            // Failed
            return response()->json([
            	'isFailed' => true
            ], 200);

        }

        // Disable 2FA
        $user->passwordSecurity->google2fa_enable = false;
        $user->passwordSecurity->save();

        // Success
        return response()->json($user->passwordSecurity, 200);
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
