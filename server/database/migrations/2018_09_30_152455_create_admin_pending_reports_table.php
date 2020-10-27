<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPendingReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_pending_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id', 32)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('post_id');
            $table->boolean('isClassifieds')->default(false);
            $table->boolean('isComment')->default(false);
            $table->boolean('isRead')->default(false);
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
        Schema::dropIfExists('admin_pending_reports');
    }
}
