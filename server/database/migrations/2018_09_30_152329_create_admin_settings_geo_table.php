<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsGeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_geo', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('isMultipleCountries')->default(true);
            $table->unsignedInteger('defaultCountry');
            $table->unsignedInteger('defaultState')->nullable();
            $table->unsignedInteger('defaultCity');
            $table->unsignedInteger('defaultCurrency');
            $table->boolean('enableStates')->default(true);
            $table->boolean('enableCities')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_geo');
    }
}
