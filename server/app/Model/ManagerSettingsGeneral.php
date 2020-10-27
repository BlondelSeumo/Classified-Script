<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ManagerSettingsGeneral extends Model
{
	const CREATED_AT = NULL;
	const UPDATED_AT = NULL;


	/**
	 * Manager settings general table
	 * @var string
	 */
	protected $table = "manager_settings_general";


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'default_currency', 
        'default_currency_locale',
        'default_timezone',
        'default_order_prefix',
        'default_order_suffix',
        'default_weight_unit',
        'default_length_unit',
        'default_volume_unit'
    ];
}
