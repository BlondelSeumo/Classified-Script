<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;
use App\Model\Plan;

class Subscription extends Model
{
    use softDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    

	const CREATED_AT = 'subscribed_at';
	const UPDATED_AT = NULL;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'user_id', 
        'plan_id',
        'transaction_id',
        'gateway',
        'price',
        'currency',
        'locale',
        'expirySoon',
        'isExpired',
        'expires_at'
    ];


    /**
     * Get user
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Get plan
     * @return [type] [description]
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
