<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Field;

class FieldClassified extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_fields_classified';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deal_id', 
        'field_id',
        'options'
    ];



    /**
     * Get fields
     * @return [type] [description]
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'id');
    }
}
