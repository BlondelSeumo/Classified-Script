<?php

namespace App\Model;

use App\Model\Category;
use App\Model\FieldCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
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
    protected $table = 'custom_fields';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'name', 
        'slug',
        'type',
        'options',
        'isSearchable',
        'isRequired'
    ];


    public function categories()
    {
        return $this->hasManyThrough(Category::class, FieldCategory::class, 'field_id', 'id');
    }
}
