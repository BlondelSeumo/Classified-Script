<?php 

namespace Packages\Uploader;

use App\Model\SettingsWatermark;
use Intervention\Image\Facades\Image;

/**
 * @author MendelManGroup <ezzaroual@mail.com>
 * @copyright 2018 MendelManGroup
 * @category Files Manager
 */
trait HasMultipleImages
{

	/**
	 * Default images extension
	 * @var string
	 */
	public $extension = 'jpg';
	

	/**
	 * Default images width
	 * @var integer
	 */
	public $width     = 800;
	

	/**
	 * Default images quality
	 * 0 - 100
	 * @var integer
	 */
	public $quality   = 100;



	/**
	 * Run functions on updating/creating
	 * @return [type] [description]
	 */
	public static function bootHasMultipleImages()
	{
		static::creating(function ($model) {
			$unique_id        = uniqueId();
			$model->unique_id = $unique_id;

            if (request()->hasFile('images')) {
            	$model->upload($unique_id);
            }
        });

        static::updating(function ($model) {

            if (request()->hasFile('images')) {
            	$model->updateImages($model->unique_id);
            }
        });
	}

	

	/**
	 * Upload all images
	 * @param  [type] $images [description]
	 * @return [type]         [description]
	 */
	public function upload($uniqueId)
	{
		// Get watermark settings
		$watermark     = SettingsWatermark::find(1);
		
		// Check if watermark enabled
		$isWatermark   = $watermark->isActive ? true : false;
		
		// Get watermark path
		$watermarkPath = $watermark->watermark == 'watermark.png' ? storage_path('app/uploads/settings/watermark/watermark.png') : storage_path('app/' . $watermark->watermark);
		
		// Path to save
		$path          = storage_path('app/uploads/' . $this->uploadPath . '/' . $uniqueId);

		if (!is_dir($path)) {
			$this->createDir($path);
		}

		foreach (request()->file('images') as $counter => $image) {
                
            $name = 'preview_'.$counter.'.'.$this->extension;

            $intervention = Image::make($image);

            // Check if image can be fit
            // if ($intervention->width() > $this->width) {
            // 	$intervention->fit($this->width);
            // }

            // check if watermark enabled
            if ($isWatermark) {
            	$intervention->insert($watermarkPath, $watermark->position, 10, 10);
            }
            
            // Save image
            $intervention->save($path."/".$name, $this->quality);
        }

        return true;
	}

	

	/**
	 * Update images all images
	 * @param  [type] $images [description]
	 * @return [type]         [description]
	 */
	public function updateImages($uniqueId)
	{
		$path = storage_path('app/uploads/' . $this->uploadPath . '/' . $uniqueId);

		// If path not exist, create new else delete old images
		if (!is_dir($path)) {
			$this->createDir($path);
		}else{
			$this->deleteFiles($path);
		}

		try {
			foreach (request()->file('images') as $counter => $image) {
			
				if ($image) {
					$name = 'preview_'.$counter.'.'.$this->extension;
	
					$intervention = Image::make($image);
	
					// Check if image can be fit
					// if ($intervention->width() > $this->width) {
					// 	$intervention->fit($this->width);
					// }
					
					// Save image
					$intervention->save($path."/".$name, 70);
				}
			}
		} catch (\Throwable $th) {
			//throw $th;
		}

        return true;
	}



	/**
	 * Create a directory if not exists
	 * @param  [type] $dir [description]
	 * @return [type]      [description]
	 */
	protected function createDir($dir)
	{
		return mkdir($dir, 0777);
	}



	/**
	 * Delete old images
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	protected function deleteFiles($dir)
	{
		if (is_dir($dir)) {
	    	$objects = scandir($dir);
	    	foreach ($objects as $object) {
		      	if ($object != "." && $object != "..") {
		        	if (filetype($dir."/".$object) == "dir") 
		           		rrmdir($dir."/".$object); 
		        	else unlink   ($dir."/".$object);
		      	}
	    	}
	    	reset($objects);
	  	}
	}
}