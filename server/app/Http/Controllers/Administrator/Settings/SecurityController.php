<?php

namespace App\Http\Controllers\Administrator\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SettingsSecurity;
use Illuminate\Support\Facades\Artisan;
use ConfigWriter;

class SecurityController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-security');
    }



    /**
     * Get security settings
     * @return [type] [description]
     */
    public function settings()
    {
    	$settings = SettingsSecurity::firstOrFail();

        $captcha  = array(
            'key'    => config('captcha.siteKey'), 
            'secret' => config('captcha.secretKey'), 
        );

    	return response()->json([
            'settings' => $settings,
            'captcha'  => $captcha
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
            'auto_approve_classifieds' => 'required|boolean',
            'auto_approve_comments'    => 'required|boolean',
            'auto_approve_stores'      => 'required|boolean',
            'enable_recaptcha'         => 'required|boolean',
            'spam'                     => 'required|boolean',
        ]);

        // Update settings
        SettingsSecurity::find(1)->update([
            'blockedUsername'        => $request->blacklist_usernames,
            'blockedWords'           => $request->blacklist_words,
            'blockedEmails'          => $request->blacklist_emails,
            'autoApproveClassifieds' => $request->auto_approve_classifieds,
            'autoApproveComments'    => $request->auto_approve_comments,
            'autoApproveStores'      => $request->auto_approve_stores,
            'isRecaptcha'            => $request->enable_recaptcha,
            'spamFilter'             => $request->spam
        ]);

        // Update captcha keys
        $footer     = new ConfigWriter('captcha');
        $footer->set('siteKey', $request->recaptcha_key);
        $footer->set('secretKey', $request->recaptcha_secret);
        $footer->save();

        // For Security Reasons
        // We have to update App key for security reasons
        Artisan::call('key:generate');
        Artisan::call('cache:clear');

        // Success
        return response()->json([], 200);
    }
}
