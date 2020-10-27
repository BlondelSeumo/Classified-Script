<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class ManagerSettingsWatermark extends Model
{
	use HasImageUploads;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * Auto upload images
     * @var boolean
     */
    protected $autoUploadImages   = true;
    
    /**
     * Upload settings
     * @var [type]
     */
    protected static $imageFields = [
        'watermark'  => [
            'width'       => 200,
            'height'      => 60,
            'crop'        => false,
            'disk'        => 'local',
            'path'        => 'uploads/managers/settings/watermark',
            'placeholder' => null,
            'rules'       => 'image|mimes:png,jpg,jpeg|max:5000',
            'auto_upload' => true,
        ]
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manager_settings_watermark';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'watermark',
        'position',
        'opacity',
        'isActive'
    ];
}
