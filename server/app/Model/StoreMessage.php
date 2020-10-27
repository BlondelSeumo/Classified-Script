<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Model\Store;

class StoreMessage extends Model
{
	const UPDATED_AT = null;


	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stores_messages';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'store_id', 
        'user_id', 
        'subject',
        'message',
        'isRead',
        'isReplied'
    ];



    /**
     * Get shops
     * @return [type] [description]
     */
    public function shop()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }


    /**
     * Get user
     * @return [type] [description]
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
