<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WorldTablesSeeder::class);
        $this->call(AdminSettingsAuthTableSeeder::class);
        $this->call(AdminSettingsFooterTableSeeder::class);
        $this->call(AdminSettingsGeneralTableSeeder::class);
        $this->call(AdminSettingsGeoTableSeeder::class);
        $this->call(AdminSettingsPaymentGatewaysTableSeeder::class);
        $this->call(AdminSettingsPostingTableSeeder::class);
        $this->call(AdminSettingsSecurityTableSeeder::class);
        $this->call(AdminSettingsSeoTableSeeder::class);
        $this->call(AdminSettingsSocialTableSeeder::class);
        $this->call(AdminSettingsUploadTableSeeder::class);
        $this->call(AdminSettingsWatermarkTableSeeder::class);
        $this->call(AdvertisementsTableSeeder::class);
        $this->call(AdvertisementsCompaniesTableSeeder::class);
        $this->call(AdminInboxTableSeeder::class);
    }
}
