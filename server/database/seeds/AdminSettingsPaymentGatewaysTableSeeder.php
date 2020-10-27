<?php

use Illuminate\Database\Seeder;

class AdminSettingsPaymentGatewaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_payment_gateways')->delete();
        
        \DB::table('admin_settings_payment_gateways')->insert(array (
            0 => 
            array (
                'id' => 1,
                'isPaypal' => 1,
            ),
        ));
        
        
    }
}