<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('comment_id')->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade')->index();
            $table->integer('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->index();
            $table->integer('flag')->nullable()->comment('1-Like,2-Dislike');
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
        Schema::dropIfExists('comment_likes');
    }
}
