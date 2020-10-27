<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class PasswordSecurity extends Model
{
	protected $table = 'password_securities';
	
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
