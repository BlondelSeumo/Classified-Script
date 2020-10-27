<?php

use Illuminate\Database\Seeder;

class AdminSettingsSocialTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_social')->delete();
        
        \DB::table('admin_settings_social')->insert(array (
            0 => 
            array (
                'id' => 1,
                'isFacebook' => 1,
                'isTwitter' => 1,
                'isGoogle' => 1,
                'isInstagram' => 1,
                'isPinterest' => 1,
                'isLinkedin' => 1,
                'isVk' => 1,
            ),
        ));
        
        
    }
}