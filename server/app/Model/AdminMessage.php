<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{

	const UPDATED_AT = null;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_inbox';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'subject',
        'unique_id',
        'message',
        'ip',
        'isRead',
        'isReplied'
    ];
}
