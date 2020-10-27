<?php

namespace App\Model;

use App\Model\Store;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 
        'product_id',
        'unique_id', 
        'user_id',
        'rating',
        'comments',
        'isActive',
        'isPending'
    ];



    /**
     * Author
     * @return [type] [description]
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Product
     * @return [type] [description]
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    /**
     * Get vendor
     * @return [type] [description]
     */
    public function vendor()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
