<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->string('name', 60);
            $table->string('slug', 60)->unique();
            $table->string('type')->default('radio');
            $table->longText('options')->nullable();
            $table->boolean('isRequired')->default(false);
            $table->boolean('isSearchable')->default(false);
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
        Schema::dropIfExists('custom_fields');
    }
}
