<?php

use Illuminate\Database\Seeder;

class AdminSettingsWatermarkTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_watermark')->delete();
        
        \DB::table('admin_settings_watermark')->insert(array (
            0 => 
            array (
                'id' => 1,
                'watermark' => 'uploads/settings/watermark/79NzfkCuproaycGWn8Ciq9Rp3jGPVRvWluN887oU.png',
                'position' => 'top',
                'isActive' => 1,
            ),
        ));
        
        
    }
}