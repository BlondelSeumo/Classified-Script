<?php

namespace App\Http\Controllers\Installation;

use ConfigWriter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class FinishController extends Controller
{
    /**
     * Finish installation
     *
     * @return void
     */
    public function finish()
    {
        // Delete installation folders and files
        //$this->deleteDir(app_path('Http/Controllers/Installation/'));

        // Change session driver
        $session   = new ConfigWriter('session');
        $session->set('driver', 'database');
        $session->save();

        // Change cache default store
        $cache     = new ConfigWriter('cache');
        $cache->set('default', 'file');
        $cache->save();

        // Clear cache
        Artisan::call('config:clear');

        // Success
        return response([]);
    }


    /**
     * Delete a dir and it contents
     *
     * @param [type] $target
     * @return void
     */
    protected function deleteDir($target)
    {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
    
            foreach( $files as $file ){
                unlink( $file );      
            }
    
            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }
}
