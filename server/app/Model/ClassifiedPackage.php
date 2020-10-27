<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassifiedPackage extends Model
{
    use SoftDeletes;


    /**
     * Table name
     * @var string
     */
    protected $table = 'classifieds_packages';


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'title', 
        'slug', 
        'type',
        'price',
        'currency',
        'locale',
        'discount',
        'days'
    ];
}
