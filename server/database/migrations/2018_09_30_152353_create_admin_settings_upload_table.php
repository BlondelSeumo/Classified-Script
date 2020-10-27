<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings_upload', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('singleImageSize')->default(4);
            $table->integer('multipleImageSize')->default(4);
            $table->boolean('isCompression')->default(false);
            $table->string('storage')->default('local');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings_upload');
    }
}
