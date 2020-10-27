<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'watchlist';


    /**
     * [$fillable description]
     * @var [type]
     */
    protected $fillable = [
    	'user_id',
    	'product_id'
    ];
}
