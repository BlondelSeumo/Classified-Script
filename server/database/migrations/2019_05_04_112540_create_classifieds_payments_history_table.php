<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifiedsPaymentsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifieds_payments_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('deal_id');
            $table->unsignedInteger('package_id');
            $table->string('transaction_id')->unique();
            $table->string('amount');
            $table->string('currency');
            $table->string('locale');
            $table->boolean('isSucceed')->default(false);
            $table->boolean('isAccepted')->default(false);
            $table->boolean('isRefused')->default(false);
            $table->boolean('isPending')->default(false);
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
        Schema::dropIfExists('classifieds_payments_history');
    }
}
