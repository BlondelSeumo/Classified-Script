<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loginBy', 20)->default('email');
            $table->boolean('needActivation')->default(false);
            $table->string('activationType')->default('email');
            $table->integer('activation_expires_after')->default(60);
            $table->integer('maxAttempts')->default(3);
            $table->integer('retries_in')->default(60);
            $table->boolean('isRegister')->default(true);
            $table->unsignedInteger('default_role_id');
            $table->unsignedInteger('default_plan_id');
            $table->string('default_sms_service', 32)->default('nexmo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_auth');
    }
}
