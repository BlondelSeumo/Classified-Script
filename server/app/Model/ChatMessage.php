<?php

namespace App\Model;

use App\Model\Room;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{

	/**
	 * Custom timestamps columns
	 */
	const UPDATED_AT = null;
	const CREATED_AT = 'sent_at';

	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_messages';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'room_id', 'user_id', 'message',
    ];


    /**
     * Get the room
     * @return [type] [description]
     */
    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }


    /**
     * Get message sender
     * @return [type] [description]
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
