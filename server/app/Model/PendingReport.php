<?php

namespace App\Model;

use App\Model\Classified;
use App\Model\Comment;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class PendingReport extends Model
{
	const UPDATED_AT = null;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_pending_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'user_id', 
        'post_id',
        'isClassifieds',
        'isComment',
        'isMessage',
        'isRead'
    ];



    /**
     * Get deal
     * @return [type] [description]
     */
    public function deal()
    {
        return $this->belongsTo(Classified::class, 'post_id');
    }


    /**
     * Get comment from reports
     * @return [type] [description]
     */
    public function comment()
    {
    	return $this->belongsTo(Comment::class, 'post_id');
    }


    /**
     * Get the reporter details
     * @return [type] [description]
     */
    public function reporter()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
