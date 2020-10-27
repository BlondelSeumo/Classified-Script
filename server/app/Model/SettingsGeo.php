<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingsGeo extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_settings_geo';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isMultipleCountries',
        'defaultCountry',
        'defaultState',
        'defaultCity',
        'defaultCurrency',
        'enableStates',
        'enableCities'
    ];



    /**
     * Get default currency
     * @return [type] [description]
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'defaultCurrency');
    }
}
