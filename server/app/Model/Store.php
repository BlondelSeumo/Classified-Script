<?php

namespace App\Model;

use App\Model\StoreMessage;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Khsing\World\Models\City;
use Khsing\World\Models\Country;
use Khsing\World\Models\Division;
use QCod\ImageUp\HasImageUploads;

class Store extends Model
{

	use HasImageUploads, SoftDeletes;

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
        'logo'  => [
			'width'       => 200,
			'height'      => 200,
			'crop'        => true,
			'disk'        => 'local',
			'path'        => 'uploads/stores/logos',
			'placeholder' => null,
			'rules'       => 'image|mimes:png,jpg,jpeg,svg|max:5000',
			'auto_upload' => true,
        ],
        'cover' => [
			'width'       => 851,
			'height'      => 315,
			'crop'        => true,
			'disk'        => 'local',
			'path'        => 'uploads/stores/covers',
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
    protected $table = 'stores';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store', 
        'user_id',
        'unique_id', 
        'subtitle',
        'description',
        'customer_email',
        'theme',
        'title',
        'logo',
        'cover',
        'country',
        'state',
        'city',
        'address1',
        'address2',
        'zip',
        'latitude',
        'longitude',
        'phone',
        'primary_locale',
        'isActive',
        'isPending',
        'isPaused',
        'isExpired',
        'deleted_at'
    ];



    /**
     * Store country
     * @return [type] [description]
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }



    /**
     * Store state
     * @return [type] [description]
     */
    public function state()
    {
        return $this->belongsTo(Division::class, 'state');
    }



    /**
     * Store city
     * @return [type] [description]
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }



    /**
     * Get store owner
     * @return [type] [description]
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsNotAdminShop($query)
    {
        return $query->where('user_id', '!=', 1);
    }


    /**
     * Get activated shops
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeActive($query)
    {
        $query->where('isActive', true)->where('isPending', false)->where('isExpired', false);
    }



    /**
     * Get shops without admin shops
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeWithoutAdminShops($query)
    {
        $query->where('user_id', '!=', 1);
    }


    /**
     * Get store messages
     * @return [type] [description]
     */
    public function messages()
    {
        return $this->hasMany(StoreMessage::class, 'store_id');
    }
}
