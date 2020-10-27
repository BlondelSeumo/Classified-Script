<?php

namespace App\Model;

use App\Model\ChatMessage;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

	/**
	 * Not update at column
	 */
	const UPDATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_rooms';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'sender_id', 'receiver_id',
    ];

    protected $with = ['sender', 'receiver'];


    public function scopeBySender($q, $sender)
    {
        $q->where('sender_id', $sender);
    }

    public function scopeByReceiver($q, $sender)
    {
        $q->where('receiver_id', $sender);
    }



    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }



    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }


    /**
     * Get room messages
     * @return [type] [description]
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }
}
