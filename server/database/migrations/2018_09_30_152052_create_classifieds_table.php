<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifiedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifieds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->string('short_id')->nullable();
            $table->string('unique_slug', 160)->unique();
            $table->unsignedInteger('user_id');
            $table->string('title', 120);
            $table->longText('description')->nullable();
            $table->integer('category_id')->unsigned();
            $table->integer('photosNumber')->nullable();
            $table->string('photosHost')->default('local');
            $table->string('video', 100)->nullable();
            $table->integer('country')->unsigned()->nullable();
            $table->integer('state')->unsigned()->nullable();
            $table->integer('city')->unsigned()->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->bigInteger('visits')->default(0);
            $table->string('price')->nullable();
            $table->string('currency', 30)->nullable();
            $table->string('locale', 10)->nullable();
            $table->boolean('isUpgraded')->default(false);
            $table->string('upgradedTo', 60)->nullable();
            $table->boolean('isPending')->default(false);
            $table->boolean('isActive')->default(true);
            $table->boolean('isArchived')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classifieds');
    }
}
