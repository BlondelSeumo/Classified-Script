<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Search extends Model
{
	use SoftDeletes;

	
	const UPDATED_AT = NULL;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'saved_search';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'search'
    ];
}
