<?php

use Illuminate\Database\Seeder;

class AdminSettingsGeoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_geo')->delete();
        
        \DB::table('admin_settings_geo')->insert(array (
            0 => 
            array (
                'id' => 1,
                'isMultipleCountries' => 1,
                'defaultCountry' => 46,
                'defaultState' => 123,
                'defaultCity' => 1420,
                'defaultCurrency' => 1,
                'enableStates' => 1,
                'enableCities' => 1,
            ),
        ));
        
        
    }
}