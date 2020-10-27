<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Store;
use App\Model\User;

class Follower extends Model
{

	const CREATED_AT = 'followed_at';
	const UPDATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'followers';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 
        'user_id', 
        'isEmailNotifications'
    ];



    /**
     * Get follower user
     * @return [type] [description]
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    /**
     * Get shop he follows
     * @return [type] [description]
     */
    public function shop()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
