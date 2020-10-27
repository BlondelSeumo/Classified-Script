<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->unsignedInteger('deal_id');
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('isPending')->default(true);
            $table->boolean('isAccepted')->default(false);
            $table->boolean('isRefused')->default(false);
            $table->timestamp('created_at');
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
        Schema::dropIfExists('offers');
    }
}
