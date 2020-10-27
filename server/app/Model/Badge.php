<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'badges';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'image'
    ];
}
