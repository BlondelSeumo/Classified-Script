<?php

namespace App\Policies\Owner;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
{
    use HandlesAuthorization;

    /**
     * First admin has access to everything
     * @param  [type] $user    [description]
     * @param  [type] $ability [description]
     * @return [type]          [description]
     */
    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }


    /**
     * Check if user has access to settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function access($user)
    {
        return $user->hasPermission('access_settings');
    }


    /**
     * Check if user has access to general settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function general($user)
    {
        return $user->hasPermission('access_general_settings');
    }


    /**
     * Check if user has access to auth settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function auth($user)
    {
        return $user->hasPermission('access_auth_settings');
    }


    /**
     * Check if user has access to smtp settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function smtp($user)
    {
        return $user->hasPermission('access_smtp_settings');
    }


    /**
     * Check if user has access to security settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function security($user)
    {
        return $user->hasPermission('access_security_settings');
    }


    /**
     * Check if user has access to geo settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function geo($user)
    {
        return $user->hasPermission('access_geo_settings');
    }


    /**
     * Check if user has access to seo settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function seo($user)
    {
        return $user->hasPermission('access_seo_settings');
    }


    /**
     * Check if user has access to upload settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function upload($user)
    {
        return $user->hasPermission('access_uploading_settings');
    }


    /**
     * Check if user has access to payments settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function payments($user)
    {
        return $user->hasPermission('access_payment_gateways_settings');
    }


    /**
     * Check if user has access to social media settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function social($user)
    {
        return $user->hasPermission('access_social_media_settings');
    }


    /**
     * Check if user has access to watermark settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function watermark($user)
    {
        return $user->hasPermission('access_watermark_settings');
    }


    /**
     * Check if user has access to footer settings
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function footer($user)
    {
        return $user->hasPermission('access_footer_settings');
    }
}
