<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Khsing\World\Models\Country;

class ManagerSettingsShippingFrom extends Model
{
    const CREATED_AT = NULL;
    const UPDATED_AT = NULL;

    /**
     * [$table description]
     * @var string
     */
    protected $table = 'manager_settings_shipping_from';

    /**
     * [$fillable description]
     * @var [type]
     */
    protected $fillable = [
    	'user_id',
    	'location_name',
    	'location_address1',
    	'location_address2',
    	'location_city',
    	'location_zip',
    	'location_country',
    	'location_region',
    	'location_phone'
    ];


    /**
     * Get country
     * @return [type] [description]
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'location_country', 'id');
    }
}
