<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifiedsStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifieds_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('deal_id');
            $table->ipAddress('ip');
            $table->string('countryCode', 2)->nullable();
            $table->string('countryName')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('continent')->nullable();
            $table->string('longitutde', 60)->nullable();
            $table->string('latitude', 60)->nullable();
            $table->string('browserName')->nullable();
            $table->string('browserVersion')->nullable();
            $table->string('browserLanguage')->nullable();
            $table->string('platformName')->nullable();
            $table->string('platformVersion')->nullable();
            $table->string('deviceName')->nullable();
            $table->integer('screenWidth')->nullable();
            $table->integer('screenHeight')->nullable();
            $table->boolean('isPhone')->default(false);
            $table->boolean('isTablet')->default(false);
            $table->boolean('isDesktop')->default(true);
            $table->boolean('isRobot')->default(false);
            $table->string('robotName')->nullable();
            $table->longText('referrer')->nullable();
            $table->longText('searchEngineKeyword')->nullable();
            $table->longText('referrerDomain')->nullable();
            $table->longText('provider')->nullable();
            $table->timestamp('first_visit');
            $table->timestamp('last_visit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classifieds_statistics');
    }
}
