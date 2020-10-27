<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsSocialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_social', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('isFacebook')->default(false);
            $table->boolean('isTwitter')->default(false);
            $table->boolean('isGoogle')->default(false);
            $table->boolean('isInstagram')->default(false);
            $table->boolean('isPinterest')->default(false);
            $table->boolean('isLinkedin')->default(false);
            $table->boolean('isVk')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_social');
    }
}
