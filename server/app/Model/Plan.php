<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use QCod\ImageUp\HasImageUploads;

class Plan extends Model
{
    use HasImageUploads, softDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        'icon'  => [
            'width'       => 100,
            'height'      => 100,
            'crop'        => false,
            'disk'        => 'local',
            'path'        => 'uploads/plans',
            'placeholder' => null,
            'rules'       => 'image|mimes:png,jpg,jpeg,svg|max:5000',
            'auto_upload' => true,
        ]
    ];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plans';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'title', 
        'subtitle',
        'description',
        'slug',
        'price',
        'currency',
        'locale',
        'plan',
        'frequency',
        'icon',
        'isRecommended',
        'discount',
        'isStores',
        'isOnlineShopping',
        'isWorkingHours',
        'isCoupons',
        'isTeams',
        'isCustomTheme',
        'isCustomWatermark',
        'isAutoshare',
        'stores_limit',
        'products_limit',
        'classifieds_limit',
        'product_images_limit',
        'classifieds_images_limit',
        'classifieds_expiry'
    ];
}
