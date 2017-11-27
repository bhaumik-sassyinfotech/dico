<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('meeting_id')->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade')->index();;
            $table->integer('group_id')->default(0)->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->index();
            $table->integer('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->index();
            $table->integer('is_admin')->default(0);
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
        Schema::dropIfExists('meeting_users');
    }
}
