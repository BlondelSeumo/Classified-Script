<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class PendingUser extends Model
{
	const UPDATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_pending_users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'isRead'
    ];


    /**
     * Get user details
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
