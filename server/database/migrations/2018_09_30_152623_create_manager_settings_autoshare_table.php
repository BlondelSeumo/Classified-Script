<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerSettingsAutoshareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_settings_autoshare', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->longText('facebook')->nullable();
            $table->longText('twitter')->nullable();
            $table->longText('telegram')->nullable();
            $table->boolean('isFacebook')->default(false);
            $table->boolean('isTwitter')->default(false);
            $table->boolean('isTelegram')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_settings_autoshare');
    }
}
