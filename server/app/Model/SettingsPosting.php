<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingsPosting extends Model
{
    const CREATED_AT = null;
	const UPDATED_AT = null;

	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_settings_posting';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deals_day',
        'deals_life',
        'deals_images'
    ];
}
