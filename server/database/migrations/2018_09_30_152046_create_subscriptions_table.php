<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->integer('user_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->string('transaction_id', 120)->nullable();
            $table->string('gateway', 60)->default('stripe');
            $table->string('price');
            $table->string('currency');
            $table->string('locale');
            $table->boolean('expirySoon')->default(false);
            $table->boolean('isExpired')->default(false);
            $table->timestamp('subscribed_at');
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('subscriptions');
    }
}
