<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('notification_id')->unsigned();
            $table->integer('user_id')->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->index();
            $table->text('notification_description');
            $table->boolean('is_read')->default(0)->comment('0 = unseen , 1 = seen');
            $table->integer('send_to');
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
        Schema::dropIfExists('notifications');
    }
}
