<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    const UPDATED_AT = null;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'replies';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'user_id', 
        'post_id',
        'comment_id',
        'reply',
        'isProduct',
        'isDeal',
        'isArticle',
        'isPending',
        'isActive',
        'isSpam',
        'deleted_at'
    ];
}
