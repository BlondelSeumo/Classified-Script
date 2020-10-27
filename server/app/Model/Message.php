<?php

namespace App\Model;

use App\Model\Classified;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id',
        'user_from',
        'user_to',
        'deal_id',
        'message',
        'isRead'
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
     * Get sender user
     * @return [type] [description]
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_from');
    }


    /**
     * Get user to
     * @return [type] [description]
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_to');
    }
}
