<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class EmailActivation extends Model
{

	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'email_activations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 
        'email', 
        'user_id',
        'used'
    ];



    /**
     * Get user
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
