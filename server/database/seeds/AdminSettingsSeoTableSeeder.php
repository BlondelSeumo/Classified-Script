<?php

use Illuminate\Database\Seeder;

class AdminSettingsSeoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_seo')->delete();
        
        \DB::table('admin_settings_seo')->insert(array (
            0 => 
            array (
                'id' => 1,
                'keywords' => 'everest,nice,php,feature,NuxtJs,VueJs',
                'description' => 'EVEREST is a php script based on Laravel framework and NuxtJs framework, allows you to create a creative classifieds site with a lot of powerful features',
                'separator' => '-',
                'dnsPrefetch' => NULL,
                'customHeaderCode' => NULL,
                'customFooterCode' => NULL,
                'isSitemap' => 1,
                'isCdn' => 0,
                'cdn' => NULL,
                'googleAnalyticsId' => NULL,
            ),
        ));
        
        
    }
}