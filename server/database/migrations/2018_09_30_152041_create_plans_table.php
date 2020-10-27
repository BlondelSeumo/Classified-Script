<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->string('title', 60);
            $table->string('slug', 60)->unique();
            $table->string('subtitle', 60)->nullable();
            $table->string('description', 100)->nullable();
            $table->string('price', 60)->nullable();
            $table->string('currency')->nullable();
            $table->string('locale', 10)->nullable();
            $table->string('frequency', 15)->nullable();
            $table->string('icon', 191)->nullable();
            $table->boolean('isRecommended')->default(false);
            $table->integer('discount')->nullable();
            $table->boolean('isStores')->default(true);
            $table->boolean('isOnlineShopping')->default(true);
            $table->boolean('isWorkingHours')->default(true);
            $table->boolean('isCoupons')->default(true);
            $table->boolean('isTeams')->default(true);
            $table->boolean('isCustomTheme')->default(true);
            $table->boolean('isCustomWatermark')->default(true);
            $table->boolean('isAutoshare')->default(true);
            $table->integer('stores_limit')->default(5)->nullable();
            $table->integer('products_limit')->default(50)->nullable();
            $table->integer('classifieds_limit')->default(50)->nullable();
            $table->integer('product_images_limit')->default(10)->nullable();
            $table->integer('classifieds_images_limit')->default(10)->nullable();
            $table->integer('classifieds_expiry')->default(365)->nullable();
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
        Schema::dropIfExists('plans');
    }
}
