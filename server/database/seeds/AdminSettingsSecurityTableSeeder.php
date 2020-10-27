<?php

use Illuminate\Database\Seeder;

class AdminSettingsSecurityTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_settings_security')->delete();
        
        \DB::table('admin_settings_security')->insert(array (
            0 => 
            array (
                'id' => 1,
                'blockedUsername' => 'root,admin,administrator,hacker,hack,javascript,js',
                'blockedWords' => NULL,
                'blockedEmails' => 'root@mail.com,admin@mail.com,admin@gmail.com',
                'blockedIPs' => NULL,
                'autoApproveClassifieds' => 0,
                'autoApproveProducts' => 0,
                'autoApproveComments' => 0,
                'autoApproveReplies' => 0,
                'autoApproveStores' => 0,
                'autoApprovePayments' => 0,
                'isRecaptcha' => 0,
                'spamFilter' => 1,
                'logErrors' => 0,
                'logHackingAttempts' => 0,
                'notifyDeveloper' => 1,
                'alertsEmail' => 0,
                'passwordMinLenght' => 8,
            ),
        ));
        
        
    }
}