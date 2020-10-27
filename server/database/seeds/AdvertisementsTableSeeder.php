<?php

use Illuminate\Database\Seeder;

class AdvertisementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('advertisements')->delete();
        
        \DB::table('advertisements')->insert(array (
            0 => 
            array (
                'id' => 1,
                'ad_deal_sidebar' => 'sidebar',
                'ad_deal_top_related' => 'top ad',
                'ad_deal_bottom_related' => 'bottom ad',
            ),
        ));
        
        
    }
}