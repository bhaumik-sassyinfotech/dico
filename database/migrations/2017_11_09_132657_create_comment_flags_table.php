<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_flags', function (Blueprint $table) {
            $table->increments('flag_id')->unsigned();
            $table->integer('comment_id')->foreign('comment_id')->references('comment_id')->on('comments')->onDelete('cascade')->index();
            $table->integer('user_id')->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->index();
            $table->string('reason','255');
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
        Schema::dropIfExists('comment_flags');
    }
}
