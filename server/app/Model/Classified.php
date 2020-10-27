<?php

namespace App\Model;


use App\Model\Category;
use App\Model\ClassifiedStatistics;
use App\Model\Comment;
use App\Model\FieldClassified;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Khsing\World\Models\City;
use Khsing\World\Models\Country;
use Khsing\World\Models\Division;
use Packages\Uploader\HasMultipleImages;

class Classified extends Model
{
    use HasMultipleImages, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    protected $uploadPath = "classifieds";


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classifieds';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'short_id', 
        'unique_slug',
        'user_id',
        'title',
        'description',
        'category_id',
        'photosNumber',
        'photosHost',
        'video',
        'country',
        'state',
        'city',
        'latitude',
        'longitude',
        'visits',
        'price',
        'currency',
        'locale',
        'isUpgraded',
        'isPending',
        'isActive',
        'isArchived',
        'upgradedTo',
        'deleted_at',
        'expires_at'
    ];



    /**
     * Get classified ad photo
     * @return [type] [description]
     */
    public function photo()
    {
        if ($this->photosNumber == 0) {
            
            // get default image
            return index('application/storage/app/uploads/default/classifieds/no-image.jpg');

        }else{

            // get first image
            return index('application/storage/app/uploads/classifieds/'.$this->unique_id.'/preview_0.jpg');

        }
    }



    /**
     * Get classified ad author
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    /**
     * Get classified ad category
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }



    /**
     * Get country
     * @return [type] [description]
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }



    /**
     * Get state
     * @return [type] [description]
     */
    public function state()
    {
        return $this->belongsTo(Division::class, 'state');
    }



    /**
     * Get city
     * @return [type] [description]
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }


    /**
     * Get deal stats
     * @return [type] [description]
     */
    public function statistics()
    {
        return $this->hasMany(ClassifiedStatistics::class, 'deal_id');
    }


    /**
     * Get deal comments
     * @return [type] [description]
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }


    /**
     * Get deal custom fields
     * @return [type] [description]
     */
    public function fields()
    {
        return $this->hasMany(FieldClassified::class, 'deal_id');
    }


    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeisNotAdminDeals($query)
    {
        return $query->where('user_id', '!=', 1);
    }


    /**
     * Get activated deals
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeActive($query)
    {
        $query->where('isActive', true)->where('isPending', false);
    }



    /**
     * Get featured deals
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeFeatured($query)
    {
        $query->where('isUpgraded', true)->where('upgradedTo', 'featured');
    }
}
