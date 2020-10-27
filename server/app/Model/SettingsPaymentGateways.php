<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingsPaymentGateways extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_settings_payment_gateways';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isPaypal'
    ];
}
