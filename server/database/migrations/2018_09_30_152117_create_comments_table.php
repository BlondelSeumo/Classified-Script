<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('post_id');
            $table->string('post_unique_id');
            $table->text('comment');
            $table->boolean('isDeal')->default(true);
            $table->boolean('isArticle')->default(false);
            $table->boolean('isPending')->default(true);
            $table->boolean('isActive')->default(false);
            $table->boolean('isSpam')->default(false);
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
        Schema::dropIfExists('comments');
    }
}
