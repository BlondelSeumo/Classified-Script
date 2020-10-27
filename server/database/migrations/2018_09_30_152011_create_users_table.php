<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->integer('role_id')->default(1);
            $table->integer('plan_id')->default(1);
            $table->string('username', 32)->unique();
            $table->string('email', 60)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60)->nullable();
            $table->integer('country')->nullable();
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('avatar', 100)->nullable();
            $table->boolean('isActive')->default(true);
            $table->boolean('isPending')->default(false);
            $table->boolean('isBlocked')->default(false);
            $table->string('provider_name')->nullable();
            $table->string('provider_id', 60)->unique()->nullable(); 
            $table->string('locale', 2)->default('en'); 
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
