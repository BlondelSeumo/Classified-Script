<?php

use Illuminate\Database\Seeder;

class AdminInboxTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_inbox')->delete();
        
        
        
    }
}