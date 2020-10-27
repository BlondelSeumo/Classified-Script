<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

	const UPDATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'group', 'name', 'permissions'
    ];


    /**
     * User have a role
     * @return [type] [description]
     */
	public function users()
    {
        return $this->hasMany(User::class);
    }
}
