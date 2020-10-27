<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsSecurityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_security', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('blockedUsername')->nullable();
            $table->longText('blockedWords')->nullable();
            $table->longText('blockedEmails')->nullable();
            $table->longText('blockedIPs')->nullable();
            $table->boolean('autoApproveClassifieds')->default(false);
            $table->boolean('autoApproveComments')->default(false);
            $table->boolean('autoApproveStores')->default(false);
            $table->boolean('isRecaptcha')->default(false);
            $table->boolean('spamFilter')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_security');
    }
}
