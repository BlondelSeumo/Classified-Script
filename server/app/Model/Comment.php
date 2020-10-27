<?php

namespace App\Model;

use App\Model\Article;
use App\Model\Classified;
use App\Model\Product;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    
	const UPDATED_AT = null;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 
        'user_id', 
        'post_id',
        'post_unique_id',
        'comment',
        'isProduct',
        'isDeal',
        'isArticle',
        'isPending',
        'isActive',
        'isSpam'
    ];


    /**
     * Get user by comment
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Get deal
     * @return [type] [description]
     */
    public function deal()
    {
        return $this->belongsTo(Classified::class, 'post_id');
    }


    /**
     * Get product
     * @return [type] [description]
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'post_id');
    }


    /**
     * Get article
     * @return [type] [description]
     */
    public function article()
    {
        $this->belongsTo(Article::class, 'post_id');
    }



    /**
     * Get comment not spams
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeNotSpam($query)
    {
        $query->where('isSpam', false);
    }


    // Get only activated comments
    public function scopeActive($query)
    {
        $query->where('isActive', true);
    }



    /**
     * Get comments without admin comments
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeWithoutAdminComments($query)
    {
        $query->where('user_id', '!=', 1);
    }
}
