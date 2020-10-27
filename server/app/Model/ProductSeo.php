<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductSeo extends Model
{

	const CREATED_AT = null;
	const UPDATED_AT = null;

	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products_seo';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 
        'description',
        'title',
    ];
}
