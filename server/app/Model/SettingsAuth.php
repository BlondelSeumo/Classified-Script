<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingsAuth extends Model
{

	const CREATED_AT = null;
	const UPDATED_AT = null;

	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_settings_auth';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loginBy',
        'needActivation',
        'activationType',
        'activation_expires_after',
        'maxAttempts',
        'retries_in',
        'isRegister',
        'default_role_id',
        'default_plan_id'
    ];
}
