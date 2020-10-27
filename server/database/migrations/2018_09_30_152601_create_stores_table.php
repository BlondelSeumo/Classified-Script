<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->string('store', 60)->unique();
            $table->unsignedInteger('user_id');
            $table->string('customer_email', 60)->nullable();
            $table->string('title', 100);
            $table->string('subtitle', 100)->nullable();
            $table->longText('description')->nullable();
            $table->string('theme')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->unsignedInteger('country')->nullable();
            $table->unsignedInteger('state')->nullable();
            $table->unsignedInteger('city')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('zip')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('phone')->nullable();
            $table->string('primary_locale', 2)->default('en');
            $table->boolean('isActive')->default(false);
            $table->boolean('isPending')->default(false);
            $table->boolean('isPaused')->default(false);
            $table->boolean('isExpired')->default(false);
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
        Schema::dropIfExists('stores');
    }
}
