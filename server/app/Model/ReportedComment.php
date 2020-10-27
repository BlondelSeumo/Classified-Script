<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReportedComment extends Model
{
	/**
	 * No updated_at column
	 */
	const UPDATED_AT = null;


	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'reported_comments';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'reported_by', 
        'comment_id',
        'isRead'
    ];
}
