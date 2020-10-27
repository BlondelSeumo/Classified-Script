<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class SettingsGeneral extends Model
{
    use HasImageUploads;
	/**
	 * UPDATED AT NULL
	 * CREATED AT NULL
	 * 
	 */
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
        'whiteLogo'  => [
            'width'       => 175,
            'height'      => 53,
            'crop'        => false,
            'disk'        => 'local',
            'path'        => 'uploads/settings/logo',
            'placeholder' => null,
            'file_input'  => 'white',
            'rules'       => 'image|mimes:svg,png,jpg,jpeg|max:5000',
            'auto_upload' => true,
        ],

        'transparentLogo'  => [
            'width'       => 175,
            'height'      => 53,
            'crop'        => false,
            'disk'        => 'local',
            'path'        => 'uploads/settings/logo',
            'placeholder' => null,
            'file_input'  => 'transparent',
            'rules'       => 'image|mimes:svg,png,jpg,jpeg|max:5000',
            'auto_upload' => true,
        ],

        'favicon'  => [
            'width'       => 30,
            'height'      => 30,
            'crop'        => true,
            'disk'        => 'local',
            'path'        => 'uploads/settings/favicon',
            'placeholder' => null,
            'file_input'  => 'white',
            'rules'       => 'image|mimes:ico,svg,png,jpg,jpeg|max:5000',
            'auto_upload' => true,
        ]
    ];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_settings_general';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'tagline',
        'email',
        'whiteLogo',
        'transparentLogo',
        'favicon',
        'storage',
        'locale',
        'timezone',
        'isRTL'
    ];
}
