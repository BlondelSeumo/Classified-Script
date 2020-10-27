<?php

use Illuminate\Database\Seeder;

class AdminSettingsUploadTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_upload')->delete();
        
        \DB::table('admin_settings_upload')->insert(array (
            0 => 
            array (
                'id' => 1,
                'singleImageSize' => 4,
                'multipleImageSize' => 4,
                'isCompression' => 1,
                'storage' => 'local',
            ),
        ));
        
        
    }
}