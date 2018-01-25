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
            $table->increments('id')->unsigned();
            $table->integer('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->index();
            $table->integer('post_id')->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->index();
            $table->string('comment_text','255');
            $table->boolean('is_anonymous')->default(0);
            $table->boolean('is_correct')->default(0);
            $table->boolean('is_correct_by_user')->default(0);
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
        Schema::dropIfExists('comments');
    }
}
