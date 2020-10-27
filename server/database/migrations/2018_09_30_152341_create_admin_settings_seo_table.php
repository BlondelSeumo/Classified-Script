<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_seo', function (Blueprint $table) {
            $table->increments('id');
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->string('separator', 1)->default('|');
            $table->longText('dnsPrefetch')->nullable();
            $table->longText('customHeaderCode')->nullable();
            $table->longText('customFooterCode')->nullable();
            $table->boolean('isSitemap')->default(false);
            $table->boolean('isCdn')->default(false);
            $table->string('cdn')->nullable();
            $table->string('googleAnalyticsId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_seo');
    }
}
