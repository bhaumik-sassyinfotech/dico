<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingCommentRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_comment_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id')->foreign('comment_id')->on('meeting_comments')->onDelete('cascade')->index()->default(0);
            $table->integer('user_id');
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
        Schema::dropIfExists('meeting_comment_replies');
    }
}
