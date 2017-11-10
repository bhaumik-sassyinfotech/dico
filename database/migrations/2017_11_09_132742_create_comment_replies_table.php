<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_replies', function (Blueprint $table) {
           $table->increments('comment_reply_id')->unsigned();
            $table->integer('comment_id')->foreign('comment_id')->references('comment_id')->on('comments')->onDelete('cascade')->index();
            $table->integer('user_id')->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->index();
            $table->text('comment_reply');
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
        Schema::dropIfExists('comment_replies');
    }
}
