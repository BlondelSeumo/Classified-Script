<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClassifiedStatistics extends Model
{
	const CREATED_AT = "first_visit";
	const UPDATED_AT = null;

	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classifieds_statistics';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deal_id', 
        'ip', 
        'countryCode',
        'countryName',
        'state',
        'city',
        'continent',
        'longitutde',
        'latitude',
        'browserName',
        'browserVersion',
        'browserLanguage',
        'platformName',
        'platformVersion',
        'deviceName',
        'screenWidth',
        'screenHeight',
        'isPhone',
        'isTablet',
        'isDesktop',
        'isRobot',
        'robotName',
        'referrer',
        'searchEngineKeyword',
        'referrerDomain',
        'provider',
        'first_visit',
        'last_visit'
    ];
}
