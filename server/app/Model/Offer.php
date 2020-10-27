<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Classified;
use App\Model\User;

class Offer extends Model
{
    use SoftDeletes;

    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * No updated at field for offers
     *
     * @var const
     */
    const UPDATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offers';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'deal_id', 
        'from_id',
        'to_id',
        'price',
        'currency',
        'locale',
        'isPending',
        'isAccepted',
        'isRefused'
    ];


    /**
     * Offer deal
     * @return [type] [description]
     */
    public function deal()
    {
        return $this->belongsTo(Classified::class, 'deal_id');
    }


    /**
     * Offer from user
     * @return [type] [description]
     */
    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }


    /**
     * Offer to user
     * @return [type] [description]
     */
    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}
