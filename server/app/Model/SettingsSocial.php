<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingsSocial extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = null;

	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_settings_social';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isFacebook', 
        'isTwitter', 
        'isGoogle',
        'isInstagram',
        'isPinterest',
        'isLinkedin',
        'isVk'
    ];
}
