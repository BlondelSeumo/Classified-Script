<?php

namespace App\Model;

use App\Model\ProductSeo;
use App\Model\Store;
use App\Model\Watchlist;
use App\Model\Category;
use Illuminate\Database\Eloquent\Model;
use Packages\Uploader\HasMultipleImages;
use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Product extends Model
{
	use HasMultipleImages, HasJsonRelationships;

	protected $uploadPath = "products";

    protected $casts = [
        'variants' => 'json',
    ];
	

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'unique_id', 
        'product_id', 
        'description',
        'title',
        'photos',
        'vendor_id',
        'slug',
        'variants',
        'isActive',
        'isPending',
        'isExpired',
        'isDeleted',
        'deleted_at'
    ];


    /**
     * Get product category
     * @return [type] [description]
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'variants->category');
    }


    /**
     * Product seo meta
     * @return [type] [description]
     */
    public function productSeo()
    {
    	return $this->hasOne(ProductSeo::class, 'product_id');
    }


    /**
     * Get product vendor
     * @return [type] [description]
     */
    public function vendor()
    {
        return $this->belongsTo(Store::class, 'vendor_id');
    }


    /**
     * Get all wishes list
     * @return [type] [description]
     */
    public function wishes()
    {
        return $this->hasMany(Watchlist::class);
    }

}
