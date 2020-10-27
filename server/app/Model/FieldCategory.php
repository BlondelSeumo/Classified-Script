<?php

namespace App\Model;

use App\Model\Category;
use App\Model\Field;
use Illuminate\Database\Eloquent\Model;

class FieldCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_fields_category';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 
        'field_id'
    ];



    /**
     * Get category
     * @return [type] [description]
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }



    /**
     * Get field
     * @return [type] [description]
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
