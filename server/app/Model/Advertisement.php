<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = null;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advertisements';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_deal_sidebar', 
        'ad_deal_top_related', 
        'ad_deal_bottom_related'
    ];
}
