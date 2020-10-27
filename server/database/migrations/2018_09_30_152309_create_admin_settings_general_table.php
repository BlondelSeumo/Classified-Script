<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsGeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_general', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('tagline')->nullable();
            $table->string('email')->nullable();
            $table->string('whiteLogo')->nullable();
            $table->string('transparentLogo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('locale', 2)->default('en');
            $table->string('timezone')->default('UTC');
            $table->boolean('isRTL')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_general');
    }
}
