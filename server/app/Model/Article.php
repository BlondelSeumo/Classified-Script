<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use QCod\ImageUp\HasImageUploads;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    
    use HasImageUploads;

    /**
     * Auto upload images
     * @var boolean
     */
    protected $autoUploadImages   = true;
    
    /**
     * Upload settings
     * @var [type]
     */
    protected static $imageFields = [
        'cover'  => [
            'width'       => 900,
            'height'      => 450,
            'crop'        => true,
            'disk'        => 'local',
            'path'        => 'uploads/articles',
            'placeholder' => null,
            'rules'       => 'image|mimes:png,jpg,jpeg|max:5000',
            'auto_upload' => true,
        ]
    ];



    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id',
        'author_id',
        'slug',
        'title',
        'content',
        'cover'
    ];


    /**
     * Get post user
     * @return [type] [description]
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }



    /**
     * Get article cover
     * @return [type] [description]
     */
    public function picture()
    {
        if (is_null($this->cover)) {
            return index('application/storage/app/uploads/default/article/no-cover.jpg');
        }else{
            return index('application/storage/app/'.$this->cover);
        }
    }
}
