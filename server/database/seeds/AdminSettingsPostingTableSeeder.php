<?php

use Illuminate\Database\Seeder;

class AdminSettingsPostingTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_posting')->delete();
        
        \DB::table('admin_settings_posting')->insert(array (
            0 => 
            array (
                'id' => 1,
                'deals_day' => 3,
                'deals_life' => 60,
                'deals_images' => 12,
            ),
        ));
        
        
    }
}