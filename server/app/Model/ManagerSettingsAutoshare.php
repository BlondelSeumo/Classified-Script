<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ManagerSettingsAutoshare extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manager_settings_autoshare';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'facebook',
        'twitter',
        'telegram',
        'isFacebook',
        'isTwitter',
        'isTelegram'
    ];
}
