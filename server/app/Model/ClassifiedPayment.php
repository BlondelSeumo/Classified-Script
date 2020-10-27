<?php

namespace App\Model;

use App\Model\Classified;
use App\Model\ClassifiedPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassifiedPayment extends Model
{
    use SoftDeletes;


    /**
     * Table name
     * @var string
     */
    protected $table = 'classifieds_payments_history';


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
        'user_id', 
        'transaction_id',
        'deal_id', 
        'package_id',
        'amount',
        'currency',
        'locale',
        'isSucceed',
        'isAccepted',
        'isRefused',
        'isPending'
    ];



    /**
     * Get deal
     * @return [type] [description]
     */
    public function deal()
    {
    	return $this->belongsTo(Classified::class, 'deal_id');
    }



    /**
     * Get package
     * @return [type] [description]
     */
    public function package()
    {
    	return $this->belongsTo(ClassifiedPackage::class, 'package_id');
    }

}
