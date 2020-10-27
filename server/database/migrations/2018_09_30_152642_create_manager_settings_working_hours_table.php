<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerSettingsWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_settings_working_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id');
            $table->boolean('isMon')->default(false);
            $table->boolean('isTue')->default(false);
            $table->boolean('isWed')->default(false);
            $table->boolean('isThu')->default(false);
            $table->boolean('isFri')->default(false);
            $table->boolean('isSat')->default(false);
            $table->boolean('isSun')->default(false);
            $table->date('monOpen')->nullable();
            $table->date('monClose')->nullable();
            $table->date('tueOpen')->nullable();
            $table->date('tueClose')->nullable();
            $table->date('wedOpen')->nullable();
            $table->date('wedClose')->nullable();
            $table->date('thuOpen')->nullable();
            $table->date('thuClose')->nullable();
            $table->date('friOpen')->nullable();
            $table->date('friClose')->nullable();
            $table->date('satOpen')->nullable();
            $table->date('satClose')->nullable();
            $table->date('sunOpen')->nullable();
            $table->date('sunClose')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_settings_working_hours');
    }
}
