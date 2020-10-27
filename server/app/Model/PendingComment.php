<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PendingComment extends Model
{

	const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_pending_comments';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment_id', 
        'isRead'
    ];
}
