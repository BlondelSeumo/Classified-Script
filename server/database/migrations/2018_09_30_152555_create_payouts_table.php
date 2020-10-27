<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('payment_account_id');
            $table->string('amount');
            $table->boolean('inProgress')->default(true);
            $table->boolean('isProgressed')->default(false);
            $table->boolean('isRefused')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('progressed_at')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }
}
