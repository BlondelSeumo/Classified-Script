<?php

use Illuminate\Database\Seeder;

class AdminSettingsAuthTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_auth')->delete();
        
        \DB::table('admin_settings_auth')->insert(array (
            0 => 
            array (
                'id'                       => 1,
                'loginBy'                  => 'username',
                'needActivation'           => 0,
                'activationType'           => 'email',
                'activation_expires_after' => 60,
                'maxAttempts'              => 3,
                'retries_in'               => 60,
                'isRegister'               => 1,
                'default_role_id'          => 1,
                'default_plan_id'          => 1,
                'default_sms_service'      => 'nexmo',
            ),
        ));
        
        
    }
}