<?php

namespace App\Model;

use App\Model\Classified;
use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class Category extends Model
{

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
        'icon'  => [
            'width'       => 50,
            'height'      => 50,
            'crop'        => true,
            'disk'        => 'local',
            'path'        => 'uploads/categories',
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
    protected $table = 'categories';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'title', 'slug',
    ];



    /**
     * Get the index name for the model.
     *
     * @return string
    */
    public function childs() {
        return $this->hasMany(Category::class, 'parent_id', 'id') ;
    }



    /**
     * Get deals in this category
     * @return [type] [description]
     */
    public function deals()
    {
        return $this->hasMany(Classified::class, 'category_id', 'id');
    }



    /**
     * Get category icon
     * @return [type] [description]
     */
    public function icon()
    {
        if (is_null($this->icon)) {
            return index('application/storage/app/uploads/default/category/no-icon.png');
        }

        return index('application/storage/app/'.$this->icon);
    }



    /**
     * Get parent categories
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeParent($query)
    {
        $query->whereParentId(false);
    }
}
