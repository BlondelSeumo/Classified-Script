<?php

use Illuminate\Database\Seeder;

class AdminSettingsGeneralTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_general')->delete();
        
        \DB::table('admin_settings_general')->insert(array (
            0 => 
            array (
                'id'              => 1,
                'title'           => 'EVEREST',
                'tagline'         => 'TOP PHP SCRIPTS',
                'email'           => 'myid@mail.com',
                'whiteLogo'       => null,
                'transparentLogo' => null,
                'favicon'         => null,
                'locale'          => 'en',
                'timezone'        => 'UTC',
                'isRTL'           =>  0,
            ),
        ));
        
        
    }
}